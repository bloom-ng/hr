<?php
/**
 * EventReminder — CLI Controller
 *
 * Sends 24-hour advance reminders for upcoming HR calendar events.
 * Triggered by a server cron job, NOT accessible via browser.
 *
 * Usage (server):
 *   php /path/to/hr/index.php cli/EventReminder send
 *
 * Cron (runs every hour):
 *   0 * * * * php /path/to/hr/index.php cli/EventReminder send >> /tmp/event_reminder.log 2>&1
 */

use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

defined('BASEPATH') or exit('No direct script access allowed');

class EventReminder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Block browser access — CLI only
        if (!$this->input->is_cli_request()) {
            show_404();
        }

        $this->config->load('config');

        $this->load->model('Events_model');
        $this->load->model('Staff_model');
        $this->load->model('PushToken_model');
        $this->load->library('email');
    }

    /**
     * Main entry point — called by cron.
     * Finds events starting in the next 24h, sends reminders, marks them as sent.
     */
    public function send()
    {
        echo "[" . date('Y-m-d H:i:s') . "] Event reminder job started.\n";

        $events = $this->Events_model->get_events_due_for_reminder();

        if (empty($events)) {
            echo "[" . date('Y-m-d H:i:s') . "] No upcoming events found. Nothing to send.\n";
            return;
        }

        echo "[" . date('Y-m-d H:i:s') . "] Found " . count($events) . " event(s) due for reminder.\n";

        // Fetch all active staff emails
        $all_staff  = $this->Staff_model->get_all_staffs();
        $staff_emails = array_filter(array_column($all_staff, 'email'));

        // Fetch all active push tokens (same as Notify.php)
        $token_rows     = $this->PushToken_model->getAllActiveTokens();
        $push_receivers = array_column($token_rows, 'token');

        foreach ($events as $event) {
            $event_id    = $event['id'];
            $event_title = $event['title'];
            $start_date  = date('D, d M Y \a\t H:i', strtotime($event['start_date']));
            $description = !empty($event['description']) ? $event['description'] : 'No description provided.';

            echo "[" . date('Y-m-d H:i:s') . "] Processing: \"$event_title\" (starts $start_date)\n";

            // ── 1. Send Email to All Staff ────────────────────────────────────────
            if (!empty($staff_emails)) {
                $email_sent = $this->send_email_reminder(
                    $staff_emails,
                    $event_title,
                    $start_date,
                    $description
                );

                if ($email_sent) {
                    echo "[" . date('Y-m-d H:i:s') . "]   ✓ Email sent to " . count($staff_emails) . " staff member(s).\n";
                } else {
                    echo "[" . date('Y-m-d H:i:s') . "]   ✗ Email sending failed.\n";
                }
            } else {
                echo "[" . date('Y-m-d H:i:s') . "]   ⚠ No staff emails found. Skipping email.\n";
            }

            // ── 2. Send Push Notification (same pattern as Notify.php) ────────────
            if (!empty($push_receivers)) {
                $push_sent = $this->send_push_reminder(
                    $push_receivers,
                    $event_title,
                    $start_date
                );

                if ($push_sent) {
                    echo "[" . date('Y-m-d H:i:s') . "]   ✓ Push sent to " . count($push_receivers) . " device(s).\n";
                } else {
                    echo "[" . date('Y-m-d H:i:s') . "]   ⚠ No push tokens registered. Skipping push.\n";
                }
            } else {
                echo "[" . date('Y-m-d H:i:s') . "]   ⚠ No push tokens registered. Skipping push.\n";
            }

            // ── 3. Mark as reminded so cron doesn't re-send ──────────────────────
            $this->Events_model->mark_reminder_sent($event_id);
            echo "[" . date('Y-m-d H:i:s') . "]   ✓ Event #$event_id marked as reminder_sent.\n";
        }

        echo "[" . date('Y-m-d H:i:s') . "] Event reminder job completed.\n";
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Private Helpers
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Send reminder email to all staff.
     * Follows the exact same pattern as Report.php::send_email_notification().
     *
     * @param array  $emails     List of staff email addresses
     * @param string $title      Event title
     * @param string $start_date Formatted start date string
     * @param string $description Event description
     * @return bool
     */
    private function send_email_reminder(array $emails, $title, $start_date, $description)
    {
        try {
            // Use same config pattern as Report.php and Equipment.php
            $config = [
                'protocol'    => $this->config->item('protocol'),
                'smtp_host'   => $this->config->item('smtp_host'),
                'smtp_port'   => $this->config->item('smtp_port'),
                'smtp_crypto' => $this->config->item('smtp_crypto'),
                'smtp_user'   => $this->config->item('smtp_user'),
                'smtp_pass'   => $this->config->item('smtp_pass'),
                'mailtype'    => $this->config->item('mailtype') ?: 'html',
                'charset'     => $this->config->item('charset') ?: 'utf-8',
                'newline'     => $this->config->item('newline') ?: "\r\n",
            ];

            $this->email->initialize($config);
            $this->email->clear();

            // Send to the first address; BCC the rest to avoid exposing all emails
            $primary_email = array_shift($emails);
            $this->email->from('support@bloomdigitmedia.com', 'Bloom EMS');
            $this->email->to($primary_email);

            if (!empty($emails)) {
                $this->email->bcc($emails);
            }

            $this->email->subject("Reminder: $title — Tomorrow");
            $this->email->message($this->build_email_body($title, $start_date, $description));

            return $this->email->send();

        } catch (Exception $e) {
            log_message('error', '[EventReminder] Email error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to all active token holders.
     * Follows the exact same pattern as Notify.php::send_push_notification().
     *
     * @param array  $receivers  Array of Expo push tokens
     * @param string $title      Event title
     * @param string $start_date Formatted start date string
     * @return bool
     */
    private function send_push_reminder(array $receivers, $title, $start_date)
    {
        try {
            $message = (new ExpoMessage([
                'title' => 'Bloom Digital',
                'body'  => 'Upcoming event reminder',
            ]))
                ->setTitle("📅 Reminder: $title")
                ->setBody("This event starts $start_date")
                ->setData(['page' => 'bloom://Events/'])
                ->setChannelId('default')
                ->setBadge(0)
                ->playSound();

            (new Expo)->send($message)->to($receivers)->push();

            return true;

        } catch (Exception $e) {
            log_message('error', '[EventReminder] Push error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build the HTML email body for the reminder.
     */
    private function build_email_body($title, $start_date, $description)
    {
        return "
            <p>Dear Team,</p>
            <p>This is a reminder that the following event is scheduled to start in the next 24 hours:</p>
            <table cellpadding='8' cellspacing='0' border='1' style='border-collapse:collapse;'>
                <tr>
                    <td><strong>Event</strong></td>
                    <td>$title</td>
                </tr>
                <tr>
                    <td><strong>Starts</strong></td>
                    <td>$start_date</td>
                </tr>
                <tr>
                    <td><strong>Details</strong></td>
                    <td>$description</td>
                </tr>
            </table>
            <br>
            <p>Please log in to the HR portal to view full event details.</p>
            <p>— Bloom EMS</p>
        ";
    }
}
