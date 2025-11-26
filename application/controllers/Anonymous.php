<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anonymous extends CI_Controller
{


    // function __construct()
    // {
    //     // parent::__construct();
    //     // if (!$this->session->userdata('logged_in')) {
    //     //     redirect(base_url() . 'login');
    //     // }
    // }

    public function index()
    {
        $data['staff_members'] = $this->Staff_model->get_all_staffs();

        $this->load->view('admin/header');
        $this->load->view('admin/list-bonus-staff', $data);
        $this->load->view('admin/footer');
    }

    public function manage()
    {
        $data['anonymous'] = $this->Anonymous_model->getAll();


        $this->load->view('admin/header');
        $this->load->view('admin/manage-anonymous', $data);
        $this->load->view('admin/footer');
    }

    public function add_page()
    {
        $this->load->view('anonymous');
    }

    public function insert()
    {
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        $this->load->model('Anonymous_model');
        $add_data = $this->Anonymous_model->insert([
            "subject" => $subject,
            "message" => $message
        ]);

        if ($add_data) {
            $this->session->set_flashdata('success', "Bonus Added Succesfully");
        } else {
            $this->session->set_flashdata('error', "Something went wrong.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function view($id)
    {
        $data['anonymous'] = $this->Anonymous_model->get($id)[0];
        $data['next_id'] = $this->Anonymous_model->getNext($id);
        $data['prev_id'] = $this->Anonymous_model->getPrevious($id);

        $this->load->view('admin/header');
        $this->load->view('admin/view-anonymous', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->Anonymous_model->delete($id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', "Anonymous Deleted Succesfully");
        } else {
            $this->session->set_flashdata('error', "Sorry, Anonymous Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
}
