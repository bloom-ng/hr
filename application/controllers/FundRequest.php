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
                    'payment_status' => 'Pending'
                ];

                $id = $this->Fund_request_model->create($data);
                if ($id) {
                    // Notify super on create
                    $this->notify_roles(['super'], 'New Fund Request', 'A new fund request has been created.', [
                        'type' => 'fund_request', 'action' => 'created', 'id' => $id
                    ]);

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

        $this->Fund_request_model->update_status($id, 'Approved');

        // Notify finance
        $this->notify_roles(['finance'], 'Fund Request Approved', 'A fund request was approved and requires payment update.', [
            'type' => 'fund_request', 'action' => 'approved', 'id' => $id
        ]);

        // Notify HOD who made the request
        $this->notify_hod_of_department($req['department_id'], 'Your Fund Request Approved', 'Your fund request has been approved.', [
            'type' => 'fund_request', 'action' => 'approved', 'id' => $id
        ]);

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

        // Notify HOD who made the request
        $this->notify_hod_of_department($req['department_id'], 'Your Fund Request Declined', 'Your fund request has been declined.', [
            'type' => 'fund_request', 'action' => 'declined', 'id' => $id
        ]);

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
            $this->session->set_flashdata('success', 'Payment status updated');
            redirect('fund-requests');
        }

        // If not POST, just go back to list
        redirect('fund-requests');
    }

    // ============== Notification Helpers ==============
    private function notify_roles(array $roles, $title, $body, $data = [])
    {
        $emails = $this->get_emails_by_roles($roles);
        foreach ($emails as $email) {
            $this->send_email($email, $title, $body);
        }
    }

    private function notify_hod_of_department($department_id, $title, $body, $data = [])
    {
        // department_tbl.staff_id is HOD's staff ID
        $department = $this->Department_model->select_department_byID($department_id);
        if (!$department || empty($department[0]['staff_id'])) return;
        $hod_staff_id = $department[0]['staff_id'];
        $hod_staff = $this->Staff_model->select_staff_byID($hod_staff_id);
        if (!$hod_staff) return;
        $hod_email = $hod_staff[0]['email'] ?? null;
        if (!$hod_email) return;
        $this->send_email($hod_email, $title, $body);
    }

    private function get_emails_by_roles(array $roles)
    {
        $emails = [];
        foreach ($roles as $role) {
            $users = $this->User_model->getWhere(['role' => $role]);
            if (!$users) continue;
            foreach ($users as $user) {
                if (!isset($user['id'])) continue;
                $staff = $this->Staff_model->getWhere(['user_id' => $user['id']]);
                if (!$staff) continue;
                foreach ($staff as $st) {
                    if (!empty($st['email'])) {
                        $emails[] = $st['email'];
                    }
                }
            }
        }
        return array_values(array_unique($emails));
    }

    private function send_email($employee_email, $subject, $message)
    {
        $this->load->library('email');

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
        $this->email->from('support@bloomdigitmedia.com', 'Bloom EMS');
        $this->email->to($employee_email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
}
