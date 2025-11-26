<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        {
            redirect(base_url().'login');
        }
        $this->load->model('Payslip_model');
    }

    public function index()
    {
        if (!in_array($this->session->userdata('role'), ["super", "finance"])) {
            redirect(base_url());
        }

        $data['periods'] = $this->Payslip_model->getPayrollPeriods();
        $this->load->view('admin/header');
        $this->load->view('admin/payslip-periods', $data);
        $this->load->view('admin/footer');
    }

    public function generate($period)
    {
        if (!in_array($this->session->userdata('role'), ["super", "finance"])) {
            redirect(base_url());
        }

        $this->Payslip_model->updateStatus($period, 1);
        $this->session->set_flashdata('success', "Payslips for $period have been generated/published successfully.");
        redirect(base_url() . 'payslip');
    }

    public function manage($period)
    {
        if (!in_array($this->session->userdata('role'), ["super", "finance"])) {
            redirect(base_url());
        }

        $data['period'] = $period;
        $data['payslips'] = $this->Payslip_model->getPayslipsByPeriod($period);
        $this->load->view('admin/header');
        $this->load->view('admin/payslip-list', $data);
        $this->load->view('admin/footer');
    }

    public function view($id)
    {
        $payslip = $this->Payslip_model->getPayslipById($id);

        if (!$payslip) {
            show_404();
        }

        // Access control: Finance/Super or the owner of the payslip
        if (!in_array($this->session->userdata('role'), ["super", "finance"]) && 
            $payslip['staff_id'] != $this->session->userdata('staff_id')) {
            show_error('You are not authorized to view this payslip.', 403);
        }

        // Staff cannot view unpublished payslips
        if (!in_array($this->session->userdata('role'), ["super", "finance"]) && 
            $payslip['payslip_status'] == 0) {
            show_error('This payslip has not been published yet.', 403);
        }

        $data['payslip'] = $payslip;
        $this->load->view('admin/payslip-view', $data);
    }

    public function my_payslips()
    {
        $data['payslips'] = $this->Payslip_model->getMyPayslips($this->session->userdata('staff_id'));
        $this->load->view('admin/header');
        $this->load->view('staff/my-payslips', $data);
        $this->load->view('admin/footer');
    }
}
