<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        } else {
            if ($this->session->userdata('role') !== "staff") {
                $data['department'] = $this->Department_model->select_departments();
                $data['staff'] = $this->Staff_model->select_staff();
                $data['leave'] = $this->Leave_model->select_leave_forApprove();
                $data['salary'] = $this->Salary_model->sum_salary();
                $data['appraisal'] = $this->Appraisal_new_model->getWhere(['status' => Appraisal_new_model::APPRAISAL_STAFF_REPLIED]);

                $this->load->view('admin/header');
                $this->load->view('admin/dashboard', $data);
                $this->load->view('admin/footer');
            } else {
                $staff = $this->session->userdata('staff_id');
                $data['leave'] = $this->Leave_model->select_leave_byStaffID($staff);
                $data['appraisal'] = $this->Appraisal_new_model->getWhere(['staff_id' => $staff, 'status' => Appraisal_new_model::APPRAISAL_HR_APPROVED]);
                $data['bonus'] = $this->Bonus_model->userBonus($staff);
                $data['commission'] = $this->Commission_model->userCommission($staff);
                $data['unpaidFine'] = $this->Deduction_model->userUnpaidFines($staff);
                $this->load->view('admin/header');
                $this->load->view('staff/dashboard', $data);
                $this->load->view('admin/footer');
            }
        }
    }

    public function login_page()
    {
        $this->load->view('login');
    }

    public function error_page()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/error_page');
        $this->load->view('admin/footer');
    }

    public function login()
    {
        $un = $this->input->post('txtusername');
        $pw = $this->input->post('txtpassword');
        $this->load->model('User_model');
        $check_login = $this->User_model->logindata($un);

        if (empty($check_login)) {
            $this->session->set_flashdata('login_error', 'Please check your username or password and try again.', 300);
            redirect(base_url() . 'login');
        }

        if ($check_login[0]['status'] != 1) {
            $this->session->set_flashdata('login_error', 'Sorry, your account is blocked.', 300);
            redirect(base_url() . 'login');
        }

        $verified = password_verify($pw, $check_login[0]['password']);

        if ($verified) {


            $data = array(
                'logged_in'  =>  true,
                'username' => $check_login[0]['username'],
                'usertype' => $check_login[0]['usertype'],
                'role' => $check_login[0]['role'],
                'userid' => $check_login[0]['id']
            );
            if ($check_login[0]['role'] == "staff") {
                $staff = $this->Staff_model->getWhere(['user_id' => $check_login[0]['id']])[0];
                $data['staff_id'] = $staff['id'];
                $data['department_id'] = $staff['department_id'];
            }
            $this->session->set_userdata($data);
            redirect('/');
        }

        $this->session->set_flashdata('login_error', 'Please check your username or password and try again.', 300);
        redirect(base_url() . 'login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . 'login');
    }
}
