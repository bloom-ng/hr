<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

    public function __construct()
    {
        $this->config = get_config();
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
        $this->load->model('Report_model');
        $this->load->model('Hod_Report_model');
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

    public function hod_manage()
    {
        $data['staff_members'] = $this->Staff_model->get_all_staffs();
        $data['departments'] = $this->Department_model->select_departments();

        $this->load->view("admin/header");
        $this->load->view("admin/manage-hod-report", $data);
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

    public function list_hod_report($id)
    {
        $departments = $this->Department_model->select_departments();

        $loggedInUserId = $this->session->userdata('userid');
        $isHod = false;

        if (isset($departments)) {
            foreach ($departments as $department) {
                if ($loggedInUserId == $department['staff_id'] || in_array($this->session->userdata('role'), array("hrm", "super"))) {
                    // User is an HoD or has HRM/Super role
                    $isHod = true;
                    break;
                }
            }
        }

        if (!$isHod) {
            redirect('/'); // Assuming 'dashboard' is the route to your dashboard page
        }

        $data['reports'] = $this->Hod_Report_model->getWhere(["department_id" => $id]);
        $data['user'] = $id;
        $data['department'] = $department = $this->Department_model->select_department_byID($id)[0];
        $data['isHod'] = $isHod;

        $this->load->view('admin/header');
        $this->load->view('admin/list-hod-report', $data);
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

    public function add_hod_report($id)
    {
        $data['department'] = $this->Department_model->select_department_byID($id)[0];

        $this->load->view('admin/header');
        $this->load->view('staff/add-hod-report', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $reportData = array(
            'employee_name' => $this->input->post('employee_name'),
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

    public function insert_hod_report()
    {
        $reportData = array(
            'staff_id' => $this->input->post('staff_id'),
            'department_id' => $this->input->post('department_id'),
            'department_name' => $this->input->post('department_name'),
            'activities' => $this->input->post('activities'),
            'achievement' => $this->input->post('achievement'),
            'growth_analysis' => $this->input->post('growth_analysis'),
            'challenges' => $this->input->post('challenges'),
            'target_for_next_month' => $this->input->post('target_for_next_month'),
            'recommendations' => $this->input->post('recommendations'),
            'conclusion' => $this->input->post('conclusion'),
            'date' => $this->input->post('date'),
            'status' => $this->Hod_Report_model::HOD_REPORT_PENDING,
        );

        $result = $this->Hod_Report_model->insert($reportData);

        if ($result) {
            $this->session->set_flashdata('success', "Report Succesfully Added");

            $this->list_hod_report($reportData['department_id']);
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

    public function edit_hod_report($id)
    {
        $data['report'] = $this->Hod_Report_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('staff/edit-hod-report', $data);
        $this->load->view('admin/footer');
    }

    public function update($id)
    {
        $reportData = array(
            'employee_name' => $this->input->post('employee_name'),
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

    public function update_hod_report($id)
    {
        $reportData = array(
            'staff_id' => $this->input->post('staff_id'),
            'department_id' => $this->input->post('department_id'),
            'department_name' => $this->input->post('department_name'),
            'activities' => $this->input->post('activities'),
            'achievement' => $this->input->post('achievement'),
            'growth_analysis' => $this->input->post('growth_analysis'),
            'challenges' => $this->input->post('challenges'),
            'target_for_next_month' => $this->input->post('target_for_next_month'),
            'recommendations' => $this->input->post('recommendations'),
            'conclusion' => $this->input->post('conclusion'),
            'date' => $this->input->post('date'),
            'status' => $this->Hod_Report_model::HOD_REPORT_PENDING,
        );

        $result = $this->Hod_Report_model->update($reportData, $id);

        if ($result) {
            $this->session->set_flashdata('success', "Report Succesfully Added");

            $this->list_hod_report($reportData["department_id"]);
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Create Report");
        }
    }

    public function view($id)
    {
        $data['report'] = $this->Report_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/view-report', $data);
        $this->load->view('admin/footer');
    }

    public function view_hod_report($id)
    {
        $data['report'] = $this->Hod_Report_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/view-hod-report', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        // Delete voucher logic here
    }

    public function send_report($id)
    {
        $report = $this->Report_model->get($id)[0];
        $name = $report['employee_name'];

        if ($report) {
            $data = $this->Report_model->update(array('status' => $this->Report_model::REPORT_REVIEW), $id);

            if ($this->db->affected_rows() > 0) {
                $this->send_email_notification($name);
                $this->session->set_flashdata('success', "Report Succesfully Sent For Review");
            } else {
                $this->session->set_flashdata('error', "Sorry, Unable To Send Report For Review");
            }
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Find Reeport");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function send_hod_report($id)
    {
        $report = $this->Hod_Report_model->get($id)[0];
        $name = $report['department_name'];

        if ($report) {
            $data = $this->Hod_Report_model->update(array('status' => $this->Hod_Report_model::HOD_REPORT_REVIEW), $id);

            if ($this->db->affected_rows() > 0) {
                $this->send_email_notification($name);
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

    public function approve_hod_report($id)
    {
        $report = $this->Hod_Report_model->get($id)[0];

        if ($report) {
            $data = $this->Hod_Report_model->update(array('status' => $this->Hod_Report_model::HOD_REPORT_APPROVED), $id);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', "Report Succesfully Approved");
            } else {
                $this->session->set_flashdata('error', "Sorry, Unable To Approve Report");
            }
            $this->list_hod_report($report["department_id"]);
        } else {
            $this->session->set_flashdata('error', "Sorry, Unable To Find Report");
            redirect(base_url() . "manage-report");
        }
    }


    private function send_email_notification($employee_name)
    {
        // Load the email library
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

        // Email content
        $this->email->from('support@bloomdigitmedia.com', 'Bloom EMS');
        $this->email->to('report@bloomdigitmedia.com');
        $this->email->subject("$employee_name Report Status Update");
        $this->email->message("$employee_name, <br><br> has sent their report for the week");

        // Send email
        $this->email->send();
    }
}
