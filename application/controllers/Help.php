<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Help extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        {
            redirect(base_url().'login');
        }
    }

    public function index()
    {
        $data['helps'] = $this->Help_model->getAll();

        $this->load->view('admin/header');
        $this->load->view('admin/manage-help', $data);
        $this->load->view('admin/footer');
    }

}
