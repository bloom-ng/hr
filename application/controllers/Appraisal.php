<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property $session
 * @property $Department_model
 * @property $Staff_model
 */
class Appraisal extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

	public function manage()
	{
		$data['staff_members']=$this->Staff_model->get_all_staffs();
		$data['departments']=$this->Department_model->select_departments();
		$this->load->view("admin/header");
		$this->load->view("admin/manage-appraisal", $data);
		$this->load->view("admin/footer");
	}

	public function add($id)
	{
		$data['staff']= $this->Staff_model->select_staff_byID($id)[0];
		$data['name'] = 'John Doe';
		$data['job_title'] = 'Software Engineer';
		$data['department_id'] = 1;

		$this->load->view('admin/header');
		$this->load->view('admin/add-appraisal', $data);
		$this->load->view('admin/footer');
	}
}
