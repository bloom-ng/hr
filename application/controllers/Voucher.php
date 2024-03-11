<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
        $this->load->model('Voucher_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['vouchers'] = $this->Voucher_model->getAll();

        $this->load->view('admin/header');
        $this->load->view('admin/manage-voucher', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/add-voucher');
        $this->load->view('admin/footer');
    }

    public function insert()
    {
    }

    public function edit()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/edit-voucher');
        $this->load->view('admin/footer');
    }

    public function update()
    {
    }

    public function view($id)
    {
        $data['vouchers'] = $this->Voucher_model->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/view-voucher', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        // Delete voucher logic here
    }
}
