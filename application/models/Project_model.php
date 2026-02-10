<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project_model extends CI_Model
{

    protected $table = 'project';
    protected $primary_key = 'id';
    protected $allowed_fields = [
        'name',
        'client_email',
        'client_phone',
        'description_of_deliverables',
        'special_request',
        'priority',
        'schedule_type',
        'schedule_date',
        'department_id',
        'status',
        'payment_status',
        'receipt_id',
        'manager_id'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');

        // Configure email settings
        $config['protocol'] = $this->config->item('protocol');
        $config['smtp_host'] = $this->config->item('smtp_host');
        $config['smtp_port'] = $this->config->item('smtp_port');
        $config['smtp_crypto'] = $this->config->item('smtp_crypto');
        $config['smtp_user'] = $this->config->item('smtp_user');
        $config['smtp_pass'] = $this->config->item('smtp_pass');
        $config['mailtype'] = $this->config->item('mailtype');
        $config['charset'] = $this->config->item('charset');
        $config['newline'] = $this->config->item('newline');

        $this->email->initialize($config);
    }

    public function get($id = null, $where = [])
    {
        if ($id) {
            return $this->db->where($this->primary_key, $id)->get($this->table)->row();
        }
        return $this->db->where($where)->get($this->table)->result();
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        $project_id = $this->db->insert_id();

        if ($project_id) {
            $this->notify_team($project_id, 'created');
        }

        return $project_id;
    }

    public function update($id, $data, $original_data = null)
    {
        $project = $this->get($id);

        // Only finance can update payment status and receipt ID
        $user_role = $this->session->userdata('role');
        if (!in_array($user_role, ['super', 'finance'])) {
            unset($data['payment_status']);
            unset($data['receipt_id']);
        }

        // If receipt_id is being updated, validate it
        if (isset($data['receipt_id']) && $data['receipt_id'] !== $project->receipt_id) {
            if (!$this->validate_receipt($data['receipt_id'])) {
                return ['success' => false, 'message' => 'Invalid receipt number'];
            }
        }

        // Only Super Admin can approve projects
        if ($user_role !== 'super' && isset($data['status']) && $data['status'] === 'approved') {
            return ['success' => false, 'message' => 'Only Super Admin can approve projects'];
        }

        $this->db->where($this->primary_key, $id);
        $result = $this->db->update($this->table, $data);

        if ($result) {
            // Check if status, priority, or payment status was updated
            $notify_fields = ['status', 'priority', 'payment_status'];
            $should_notify = false;

            foreach ($notify_fields as $field) {
                if (isset($data[$field]) && $original_data && $original_data->$field != $data[$field]) {
                    $should_notify = true;
                    break;
                }
            }

            if ($should_notify) {
                $this->notify_team($id, 'updated');
            }
        }

        return $result;
    }

    public function delete($id)
    {
        // Check if project exists first
        $project = $this->get($id);
        if (!$project) {
            return false;
        }

        // Delete the project
        $this->db->where($this->primary_key, $id);
        $result = $this->db->delete($this->table);

        // Return true if deletion was successful
        return $result && $this->db->affected_rows() > 0;
    }

    public function get_by_department($department_id)
    {
        return $this->db->where('department_id', $department_id)->get($this->table)->result();
    }

    public function get_by_manager($manager_id)
    {
        return $this->db->where('manager_id', $manager_id)->get($this->table)->result();
    }

    protected function notify_team($project_id, $action)
    {
        $project = $this->get($project_id);
        if (!$project) return;

        $subject = "Project " . html_escape($project->name) . " has been {$action}";

        // Build status/priority badge colors for email
        $status_colors = [
            'pending' => '#EAB308', 'approved' => '#60A5FA', 'in-progress' => '#3B82F6',
            'on-hold' => '#F97316', 'delivered' => '#22C55E', 'cancelled' => '#EF4444'
        ];
        $priority_colors = ['low' => '#3B82F6', 'medium' => '#EAB308', 'high' => '#EF4444'];
        $payment_colors = ['pending' => '#EAB308', 'paid' => '#22C55E', 'refunded' => '#EF4444'];

        $status_color = $status_colors[$project->status] ?? '#6B7280';
        $priority_color = $priority_colors[$project->priority] ?? '#6B7280';
        $payment_color = $payment_colors[$project->payment_status] ?? '#6B7280';

        $status_label = ucfirst(str_replace('-', ' ', $project->status));
        $priority_label = ucfirst($project->priority);
        $payment_label = ucfirst($project->payment_status);
        $project_url = site_url("projects/view/{$project->id}");

        $message = '
        <!DOCTYPE html>
        <html>
        <head><meta charset="utf-8"></head>
        <body style="margin:0;padding:0;background-color:#1a1a1a;font-family:Arial,Helvetica,sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;padding:30px 0;">
                <tr><td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background-color:#2C2C2C;border-radius:8px;overflow:hidden;">
                        <!-- Header -->
                        <tr>
                            <td style="background-color:#DA7F00;padding:24px 30px;">
                                <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">Bloom HR</h1>
                            </td>
                        </tr>
                        <!-- Body -->
                        <tr>
                            <td style="padding:30px;">
                                <h2 style="margin:0 0 8px;color:#ffffff;font-size:18px;">Project ' . ucfirst($action) . '</h2>
                                <p style="margin:0 0 24px;color:#9CA3AF;font-size:14px;">The project <strong style="color:#DA7F00;">' . html_escape($project->name) . '</strong> has been ' . $action . '.</p>

                                <!-- Details Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#3E3E3E;border-radius:6px;margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;width:140px;">Project Name</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#ffffff;font-size:14px;font-weight:600;">' . html_escape($project->name) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Status</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;">
                                            <span style="background-color:' . $status_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $status_label . '</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Priority</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;">
                                            <span style="background-color:' . $priority_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $priority_label . '</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;color:#9CA3AF;font-size:13px;">Payment Status</td>
                                        <td style="padding:14px 20px;">
                                            <span style="background-color:' . $payment_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $payment_label . '</span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- CTA Button -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr><td align="center">
                                        <a href="' . $project_url . '" style="display:inline-block;background-color:#DA7F00;color:#ffffff;text-decoration:none;padding:12px 32px;border-radius:6px;font-size:14px;font-weight:600;">
                                            View Project Details
                                        </a>
                                    </td></tr>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td style="background-color:#1a1a1a;padding:20px 30px;text-align:center;">
                                <p style="margin:0;color:#6B7280;font-size:12px;">&copy; ' . date('Y') . ' Bloom Digit Media. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td></tr>
            </table>
        </body>
        </html>';

        // Collect all recipient emails (deduplicated)
        $recipients = ["agharayetseyi@bloomdigitmedia.com", "hr@bloomdigitmedia.com", "finance@bloomdigitmedia.com", "davidaremu@bloomdigitmedia.com"];

        // 2. Get department HOD
        $departments = $this->Department_model->select_departments();
        if (!empty($departments)) {
            foreach ($departments as $department) {
                if ($department['id'] == $project->department_id && !empty($department['staff_id'])) {
                    $hod = $this->Staff_model->select_staff_byID($department['staff_id']);
                    if (!empty($hod) && !empty($hod[0]['email'])) {
                        $recipients[$hod[0]['email']] = $hod[0]['email'];
                    }
                    break;
                }
            }
        }

        // 3. Get project manager
        $manager = $this->Staff_model->select_staff_byID($project->manager_id);
        if (!empty($manager) && !empty($manager[0]['email'])) {
            $recipients[$manager[0]['email']] = $manager[0]['email'];
        }

        // Send email to all unique recipients
        foreach ($recipients as $email) {
            $this->email->clear();
            $this->email->to($email);
            $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
        }
    }

    /**
     * Notify the project manager when their project has been approved
     */
    public function notify_manager_approved($project_id)
    {
        $project = $this->get($project_id);
        if (!$project) {
            return false;
        }

        // Get project manager details
        $manager = $this->Staff_model->select_staff_byID($project->manager_id);
        if (!$manager || empty($manager[0]['email'])) {
            log_message('error', 'Could not notify manager for approved project: Manager not found or no email');
            return false;
        }

        $manager = $manager[0];
        $subject = "Project Approved: " . html_escape($project->name);
        
        $priority_colors = ['low' => '#3B82F6', 'medium' => '#EAB308', 'high' => '#EF4444'];
        $priority_color = $priority_colors[$project->priority] ?? '#6B7280';
        $priority_label = ucfirst($project->priority);
        $project_url = site_url("projects/view/{$project->id}");

        $message = '
        <!DOCTYPE html>
        <html>
        <head><meta charset="utf-8"></head>
        <body style="margin:0;padding:0;background-color:#1a1a1a;font-family:Arial,Helvetica,sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#1a1a1a;padding:30px 0;">
                <tr><td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background-color:#2C2C2C;border-radius:8px;overflow:hidden;">
                        <!-- Header -->
                        <tr>
                            <td style="background-color:#DA7F00;padding:24px 30px;">
                                <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">Bloom HR</h1>
                            </td>
                        </tr>
                        <!-- Body -->
                        <tr>
                            <td style="padding:30px;">
                                <h2 style="margin:0 0 8px;color:#ffffff;font-size:18px;">Project Approved &#10003;</h2>
                                <p style="margin:0 0 6px;color:#9CA3AF;font-size:14px;">Dear <strong style="color:#ffffff;">' . html_escape($manager['staff_name']) . '</strong>,</p>
                                <p style="margin:0 0 24px;color:#9CA3AF;font-size:14px;">Great news! Your project <strong style="color:#DA7F00;">' . html_escape($project->name) . '</strong> has been approved. You can now proceed with execution.</p>

                                <!-- Details Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#3E3E3E;border-radius:6px;margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;width:140px;">Project Name</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#ffffff;font-size:14px;font-weight:600;">' . html_escape($project->name) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Status</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;">
                                            <span style="background-color:#22C55E;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">Approved</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;color:#9CA3AF;font-size:13px;">Priority</td>
                                        <td style="padding:14px 20px;">
                                            <span style="background-color:' . $priority_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $priority_label . '</span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- CTA Button -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr><td align="center">
                                        <a href="' . $project_url . '" style="display:inline-block;background-color:#DA7F00;color:#ffffff;text-decoration:none;padding:12px 32px;border-radius:6px;font-size:14px;font-weight:600;">
                                            View Project Details
                                        </a>
                                    </td></tr>
                                </table>
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td style="background-color:#1a1a1a;padding:20px 30px;text-align:center;">
                                <p style="margin:0;color:#6B7280;font-size:12px;">&copy; ' . date('Y') . ' Bloom Digit Media. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td></tr>
            </table>
        </body>
        </html>';

        $this->email->clear();
        $this->email->to($manager['email']);
        $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
        $this->email->subject($subject);
        $this->email->message($message);
        
        if ($this->email->send()) {
            log_message('info', "Project approval notification sent to manager: {$manager['email']} for project ID: {$project_id}");
            return true;
        } else {
            log_message('error', 'Failed to send project approval notification: ' . $this->email->print_debugger(['headers']));
            return false;
        }
    }

    public function validate_receipt($receipt_id)
    {
        if (empty($receipt_id)) {
            return false;
        }

        // Make API call to validate receipt
        $api_url = "https://ads.bloomdigitmedia.com/api/receipt/confirm/{$receipt_id}";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($curl_error) {
            log_message('error', 'Receipt validation cURL error: ' . $curl_error);
            return false;
        }

        // Check HTTP status code
        if ($http_code !== 200) {
            log_message('error', 'Receipt validation API returned HTTP code: ' . $http_code);
            return false;
        }

        // Parse JSON response
        $result = json_decode($response, true);


        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Receipt validation API returned invalid JSON');
            return false;
        }

        // Check if the response indicates success
        if (isset($result['status']) && $result['status'] === 'success' && isset($result['data'])) {
            // Log successful validation for audit purposes
            log_message('info', 'Receipt validated successfully: ' . $receipt_id);

            return $result;
        }

        // Log failure reason
        $message = isset($result['message']) ? $result['message'] : 'Unknown error';
        log_message('error', 'Receipt validation failed: ' . $message);
        return false;
    }
}
