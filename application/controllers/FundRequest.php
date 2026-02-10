<?php

defined('BASEPATH') or exit('No direct script access allowed');

class FundRequest extends MY_Controller
{
    private $staff_id;
    private $role;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }

        $this->staff_id = $this->session->userdata('userid');
        $this->role = $this->session->userdata('role');
    }

    public function index()
    {
        $data = [];
        $data['title'] = 'Fund Requests';

        // Admin (super, hrm) and Finance can view all
        if (in_array($this->role, ['super', 'hrm', 'finance'])) {
            $data['requests'] = $this->Fund_request_model->list_all();
            $data['can_create'] = false; // Only HODs can create
        } else {
            // HOD: only view requests in their department
            $is_hod = $this->Department_model->is_head_of_department($this->session->userdata('staff_id'));
            if ($is_hod) {
                $dept_id = $this->session->userdata('department_id');
                $data['requests'] = $this->Fund_request_model->list_by_department($dept_id);
                $data['can_create'] = true;
            } else {
                show_error('Unauthorized access', 403);
                return;
            }
        }

        $this->load->view('admin/header', $data);
        $this->load->view('admin/fund_requests/index', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        // Only HOD can create a request
        $is_hod = $this->Department_model->is_head_of_department($this->session->userdata('staff_id'));
        if (!$is_hod) {
            show_error('Only Head of Department can create fund requests', 403);
            return;
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('message', 'Message', 'required|max_length[1000]');

            if ($this->form_validation->run() === TRUE) {
                $dept_id = $this->session->userdata('department_id');
                $staff_id = $this->session->userdata('staff_id');

                $data = [
                    'department_id' => $dept_id,
                    'staff_id' => $staff_id,
                    'amount' => $this->input->post('amount', TRUE),
                    'message' => $this->input->post('message', TRUE),
                    'status' => 'Pending',
                    'payment_status' => 'Pending',
                    'approved_amount' => $this->input->post('amount', TRUE) // Default to requested amount
                ];

                $id = $this->Fund_request_model->create($data);
                if ($id) {
                    // Get department name for the email
                    $dept_name = $this->_get_department_name($dept_id);
                    $creator_name = $this->_get_staff_name($staff_id);

                    // Notify fixed recipients on create
                    $notify_emails = ['agharayetseyi@bloomdigitmedia.com', 'finance@bloomdigitmedia.com'];
                    $subject = 'New Fund Request Created';
                    $email_body = $this->_build_fund_request_email(
                        'New Fund Request',
                        "A new fund request has been submitted by <strong style=\"color:#DA7F00;\">{$creator_name}</strong>.",
                        [
                            'department' => $dept_name,
                            'amount' => $data['amount'],
                            'status' => 'Pending',
                            'payment_status' => 'Pending',
                            'message' => $data['message'],
                        ],
                        $id
                    );
                    $this->_send_notifications($notify_emails, $subject, $email_body);

                    $this->session->set_flashdata('success', 'Fund request created successfully');
                    redirect('fund-requests');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create fund request');
                    redirect('fund-requests/create');
                }
            }
        }

        // Render form on GET
        $data = ['title' => 'Create Fund Request'];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/fund_requests/create', $data);
        $this->load->view('admin/footer');
    }

    public function approve($id)
    {
        // Only super can approve
        if ($this->role !== 'super') {
            show_error('Only Super Admin can approve fund requests', 403);
            return;
        }

        $req = $this->Fund_request_model->get($id);
        if (!$req || $req['status'] !== 'Pending') {
            show_404();
        }

        if ($req['status'] === 'Approved') {
            $this->session->set_flashdata('error', 'Request already approved and cannot be modified.');
            redirect('fund-requests/view/' . $id);
        }

        $approved_amount = $this->input->post('approved_amount');
        if (!$approved_amount || $approved_amount <= 0) {
             $approved_amount = $req['amount'];
        }

        $this->Fund_request_model->update($id, [
            'status' => 'Approved',
            'approved_amount' => $approved_amount
        ]);

        // Notify fixed recipients + creator
        $dept_name = $this->_get_department_name($req['department_id']);
        $creator_name = $this->_get_staff_name($req['staff_id']);
        $notify_emails = $this->_get_notify_list_with_creator($req['staff_id']);
        $subject = 'Fund Request Approved';
        $email_body = $this->_build_fund_request_email(
            'Fund Request Approved &#10003;',
            "The fund request from <strong style=\"color:#DA7F00;\">{$creator_name}</strong> has been approved.",
            [
                'department' => $dept_name,
                'amount' => $req['amount'],
                'approved_amount' => $approved_amount,
                'status' => 'Approved',
                'payment_status' => $req['payment_status'],
                'message' => $req['message'],
            ],
            $id
        );
        $this->_send_notifications($notify_emails, $subject, $email_body);

        $this->session->set_flashdata('success', 'Fund request approved');
        redirect('fund-requests');
    }

    public function view($id)
    {
        $req = $this->Fund_request_model->get($id);
        if (!$req) { show_404(); }

        $can_view_all = in_array($this->role, ['super', 'hrm', 'finance']);
        if (!$can_view_all) {
            $is_hod = $this->Department_model->is_head_of_department($this->session->userdata('staff_id'));
            if (!$is_hod || (int)$this->session->userdata('department_id') !== (int)$req['department_id']) {
                show_error('Unauthorized access', 403);
                return;
            }
        }

        $data = [
            'title' => 'Fund Request Details',
            'request' => $req
        ];

        $this->load->view('admin/header', $data);
        $this->load->view('admin/fund_requests/view', $data);
        $this->load->view('admin/footer');
    }

    public function decline($id)
    {
        // Only super can decline
        if ($this->role !== 'super') {
            show_error('Only Super Admin can decline fund requests', 403);
            return;
        }

        $req = $this->Fund_request_model->get($id);
        if (!$req || $req['status'] !== 'Pending') {
            show_404();
        }

        $this->Fund_request_model->update_status($id, 'Rejected');

        // Notify fixed recipients + creator
        $dept_name = $this->_get_department_name($req['department_id']);
        $creator_name = $this->_get_staff_name($req['staff_id']);
        $notify_emails = $this->_get_notify_list_with_creator($req['staff_id']);
        $subject = 'Fund Request Declined';
        $email_body = $this->_build_fund_request_email(
            'Fund Request Declined &#10007;',
            "The fund request from <strong style=\"color:#DA7F00;\">{$creator_name}</strong> has been declined.",
            [
                'department' => $dept_name,
                'amount' => $req['amount'],
                'status' => 'Rejected',
                'payment_status' => $req['payment_status'],
                'message' => $req['message'],
            ],
            $id
        );
        $this->_send_notifications($notify_emails, $subject, $email_body);

        $this->session->set_flashdata('success', 'Fund request declined');
        redirect('fund-requests');
    }

    public function update_payment($id)
    {
        // Only finance can update payment status
        if (!in_array($this->role, ['finance'])) {
            show_error('Only Finance can update payment status', 403);
            return;
        }

        $req = $this->Fund_request_model->get($id);
        if (!$req) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $payment_status = $this->input->post('payment_status', TRUE);
            $allowed = ['Pending', 'Paid', 'Declined'];
            if (!in_array($payment_status, $allowed)) {
                $this->session->set_flashdata('error', 'Invalid payment status');
                redirect('fund-requests');
            }

            $this->Fund_request_model->update_payment_status($id, $payment_status);

            // Notify fixed recipients + creator
            $dept_name = $this->_get_department_name($req['department_id']);
            $creator_name = $this->_get_staff_name($req['staff_id']);
            $notify_emails = $this->_get_notify_list_with_creator($req['staff_id']);
            $subject = 'Fund Request Payment Updated';
            $email_body = $this->_build_fund_request_email(
                'Payment Status Updated',
                "The payment status for the fund request from <strong style=\"color:#DA7F00;\">{$creator_name}</strong> has been updated to <strong>{$payment_status}</strong>.",
                [
                    'department' => $dept_name,
                    'amount' => $req['amount'],
                    'approved_amount' => $req['approved_amount'] ?? null,
                    'status' => $req['status'],
                    'payment_status' => $payment_status,
                    'message' => $req['message'],
                ],
                $id
            );
            $this->_send_notifications($notify_emails, $subject, $email_body);

            $this->session->set_flashdata('success', 'Payment status updated');
            redirect('fund-requests');
        }

        // If not POST, just go back to list
        redirect('fund-requests');
    }

    // ============== Notification Helpers ==============

    /**
     * Get the two fixed notify addresses plus the creator's email (deduplicated).
     */
    private function _get_notify_list_with_creator($creator_staff_id)
    {
        $emails = ['agharayetseyi@bloomdigitmedia.com', 'finance@bloomdigitmedia.com'];
        $creator_email = $this->_get_staff_email($creator_staff_id);
        if ($creator_email) {
            $emails[] = $creator_email;
        }
        return array_values(array_unique($emails));
    }

    /**
     * Look up a staff member's email by their staff_id.
     */
    private function _get_staff_email($staff_id)
    {
        $staff = $this->Staff_model->select_staff_byID($staff_id);
        if (!empty($staff) && !empty($staff[0]['email'])) {
            return $staff[0]['email'];
        }
        return null;
    }

    /**
     * Look up a staff member's name by their staff_id.
     */
    private function _get_staff_name($staff_id)
    {
        $staff = $this->Staff_model->select_staff_byID($staff_id);
        if (!empty($staff) && !empty($staff[0]['staff_name'])) {
            return $staff[0]['staff_name'];
        }
        return 'Unknown';
    }

    /**
     * Look up a department name by its ID.
     */
    private function _get_department_name($department_id)
    {
        $department = $this->Department_model->select_department_byID($department_id);
        if (!empty($department) && !empty($department[0]['department'])) {
            return $department[0]['department'];
        }
        return 'N/A';
    }

    /**
     * Send an email to every address in the list.
     */
    private function _send_notifications(array $emails, $subject, $html_body)
    {
        foreach ($emails as $email) {
            $this->_send_email($email, $subject, $html_body);
        }
    }

    /**
     * Build a styled HTML email body for a fund request notification.
     *
     * @param string $heading   Main heading, e.g. "New Fund Request"
     * @param string $intro     HTML intro sentence below the heading
     * @param array  $details   Associative array with keys: department, amount,
     *                          approved_amount (optional), status, payment_status, message
     * @param int    $request_id  The fund request ID (for the CTA link)
     * @return string  Full HTML email
     */
    private function _build_fund_request_email($heading, $intro, $details, $request_id)
    {
        $status_colors = [
            'Pending' => '#EAB308', 'Approved' => '#22C55E', 'Rejected' => '#EF4444'
        ];
        $payment_colors = [
            'Pending' => '#EAB308', 'Paid' => '#22C55E', 'Declined' => '#EF4444'
        ];

        $status = $details['status'] ?? 'Pending';
        $payment = $details['payment_status'] ?? 'Pending';
        $status_color = $status_colors[$status] ?? '#6B7280';
        $payment_color = $payment_colors[$payment] ?? '#6B7280';
        $amount = number_format((float)($details['amount'] ?? 0), 2);
        $department = html_escape($details['department'] ?? 'N/A');
        $message_text = html_escape($details['message'] ?? '');
        $view_url = site_url("fund-requests/view/{$request_id}");

        // Build the approved-amount row only if present
        $approved_row = '';
        if (!empty($details['approved_amount'])) {
            $approved_amt = number_format((float)$details['approved_amount'], 2);
            $approved_row = '
                <tr>
                    <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Approved Amount</td>
                    <td style="padding:14px 20px;border-bottom:1px solid #555;color:#22C55E;font-size:14px;font-weight:700;">₦' . $approved_amt . '</td>
                </tr>';
        }

        return '
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
                                <h2 style="margin:0 0 8px;color:#ffffff;font-size:18px;">' . $heading . '</h2>
                                <p style="margin:0 0 24px;color:#9CA3AF;font-size:14px;">' . $intro . '</p>

                                <!-- Details Table -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#3E3E3E;border-radius:6px;margin-bottom:24px;">
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;width:160px;">Department</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#ffffff;font-size:14px;font-weight:600;">' . $department . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Requested Amount</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#ffffff;font-size:14px;font-weight:700;">₦' . $amount . '</td>
                                    </tr>
                                    ' . $approved_row . '
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Status</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;">
                                            <span style="background-color:' . $status_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $status . '</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;color:#9CA3AF;font-size:13px;">Payment Status</td>
                                        <td style="padding:14px 20px;border-bottom:1px solid #555;">
                                            <span style="background-color:' . $payment_color . ';color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">' . $payment . '</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 20px;color:#9CA3AF;font-size:13px;">Message</td>
                                        <td style="padding:14px 20px;color:#D1D5DB;font-size:13px;line-height:1.5;">' . $message_text . '</td>
                                    </tr>
                                </table>

                                <!-- CTA Button -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr><td align="center">
                                        <a href="' . $view_url . '" style="display:inline-block;background-color:#DA7F00;color:#ffffff;text-decoration:none;padding:12px 32px;border-radius:6px;font-size:14px;font-weight:600;">
                                            View Fund Request
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
    }

    /**
     * Send a single HTML email.
     */
    private function _send_email($employee_email, $subject, $message)
    {
        $this->load->library('email');

        $config['protocol'] = $this->config->item('protocol');
        $config['smtp_host'] = $this->config->item('smtp_host');
        $config['smtp_port'] = $this->config->item('smtp_port');
        $config['smtp_crypto'] = $this->config->item('smtp_crypto');
        $config['smtp_user'] = $this->config->item('smtp_user');
        $config['smtp_pass'] = $this->config->item('smtp_pass');
        $config['mailtype'] = 'html';
        $config['charset'] = $this->config->item('charset');
        $config['newline'] = $this->config->item('newline');

        $this->email->initialize($config);
        $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
        $this->email->to($employee_email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
}
