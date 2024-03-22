<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
        $this->load->model('Report_model');
        $this->load->helper('url');
    }

    public function manage()
    {
        $data['staff_members'] = $this->Staff_model->get_all_staffs();
        $data['departments'] = $this->Department_model->select_departments();

        $this->load->view("admin/header");
        $this->load->view("admin/manage-report", $data);
        $this->load->view("admin/footer");
    }

    public function index($id)
    {
        $data['reports'] = $this->Report_model->getWhere(["staff_id" => $id]);
        $data['user'] = $id;

        $this->load->view('admin/header');
        $this->load->view('admin/list-report', $data);
        $this->load->view('admin/footer');
    }

    public function add($id)
    {
        $data['staff'] = $this->Staff_model->select_staff_byID($id)[0];
        $data['department'] = $this->Department_model->select_department_byID($data['staff']['department_id'])[0];

        $this->load->view('admin/header');
        $this->load->view('staff/add-report', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $reportData = array(
            'employee_name' => $this->input->post('employee_name'),
            'status' => $this->input->post('status'),
            'staff_id' => $this->input->post('staff_id'),
            'title' => $this->input->post('title'),
            'department' => $this->input->post('department'),
            'operation_unit' => $this->input->post('operation_unit'),
            'supervisor' => $this->input->post('supervisor'),
            'team_lead' => $this->input->post('team_lead'),
            'date' => $this->input->post('date'), // Assuming you have a date input field
            'day_1_task' => $this->input->post('day_1_task'),
            'day_1_total_hours' => $this->input->post('day_1_total_hours'),
            'day_2_task' => $this->input->post('day_2_task'),
            'day_2_total_hours' => $this->input->post('day_2_total_hours'),
            'day_3_task' => $this->input->post('day_3_task'),
            'day_3_total_hours' => $this->input->post('day_3_total_hours'),
            'day_4_task' => $this->input->post('day_4_task'),
            'day_4_total_hours' => $this->input->post('day_4_total_hours'),
            'day_5_task' => $this->input->post('day_5_task'),
            'day_5_total_hours' => $this->input->post('day_5_total_hours'),
            'status' => $this->Report_model::REPORT_PENDING,
        );

        $result = $this->Report_model->insert($reportData);

        if ($result) {
            $this->session->set_flashdata('success', "Report Succesfully Added");

            $this->index($reportData['staff_id']);
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Create Report");
        }
    }

    public function edit($id)
    {
        $data['report'] = $this->Report_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('staff/edit-report', $data);
        $this->load->view('admin/footer');
    }

    public function update($id)
    {
        $reportData = array(
            'employee_name' => $this->input->post('employee_name'),
            'status' => $this->input->post('status'),
            'staff_id' => $this->input->post('staff_id'),
            'title' => $this->input->post('title'),
            'department' => $this->input->post('department'),
            'operation_unit' => $this->input->post('operation_unit'),
            'supervisor' => $this->input->post('supervisor'),
            'team_lead' => $this->input->post('team_lead'),
            'date' => $this->input->post('date'), // Assuming you have a date input field
            'day_1_task' => $this->input->post('day_1_task'),
            'day_1_total_hours' => $this->input->post('day_1_total_hours'),
            'day_2_task' => $this->input->post('day_2_task'),
            'day_2_total_hours' => $this->input->post('day_2_total_hours'),
            'day_3_task' => $this->input->post('day_3_task'),
            'day_3_total_hours' => $this->input->post('day_3_total_hours'),
            'day_4_task' => $this->input->post('day_4_task'),
            'day_4_total_hours' => $this->input->post('day_4_total_hours'),
            'day_5_task' => $this->input->post('day_5_task'),
            'day_5_total_hours' => $this->input->post('day_5_total_hours'),
            'status' => $this->Report_model::REPORT_PENDING,
        );

        $result = $this->Report_model->update($reportData, $id);

        if ($result) {
            $this->session->set_flashdata('success', "Report Succesfully Edited");

            $this->index($reportData['staff_id']);
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Edit Report");
        }
    }

    public function view($id)
    {
        $data['report'] = $this->Report_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/view-report', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        // Delete voucher logic here
    }

    public function send_report($id)
    {
        $report = $this->Report_model->get($id)[0];

        if ($report) {
            $data = $this->Report_model->update(array('status' => $this->Report_model::REPORT_REVIEW), $id);

            if ($this->db->affected_rows() > 0) {
                $this->send_email_notification($report['employee_name']);
                $this->session->set_flashdata('success', "Report Succesfully Sent For Review");
            } else {
                $this->session->set_flashdata('error', "Sorry, Unable To Send Report For Review");
            }
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Find Reeport");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function approve_report($id)
    {
        $report = $this->Report_model->get($id);

        if ($report) {
            $data = $this->Report_model->update(array('status' => $this->Report_model::REPORT_APPROVED), $id);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', "Report Succesfully Approved");
            } else {
                $this->session->set_flashdata('error', "Sorry, Unable To Approve Report");
            }
            redirect(base_url() . "manage-report");
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Find Report");
            redirect(base_url() . "manage-report");
        }
    }


    private function send_email_notification($employee_name)
    {
        // Load the email library
        $this->load->library('email');



        $this->email->initialize($config);

        // Email content
        $this->email->from('support@bloomdigitmedia.com', 'Bloom EMS');
        $this->email->to('report@bloomdigitmedia.com');
        $this->email->subject("$employee_name Report Status Update");
        $this->email->message("$employee_name, <br><br> has sent report");

        // Send email
        $this->email->send();
    }
}
