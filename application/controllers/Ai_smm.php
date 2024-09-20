<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_smm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        $data['title'] = 'Manage AI-SMM';
        $data['page'] = 'AI-SMM';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/ai_smm', $data);
        $this->load->view('admin/footer');
    }

    public function generate() {
        $input = $this->input->post('ai_input');
        
        // Here you would typically call an AI service to generate content
        // For now, we'll just echo back the input as a placeholder
        $generated_content = "Generated content based on: \n\n" . $input;
        
        $data['title'] = 'AI-SMM';
        $data['page'] = 'AI-SMM';
        $data['generated_content'] = $generated_content;
        
        $this->load->view('admin/header', $data);
        $this->load->view('admin/ai_smm', $data);
        $this->load->view('admin/footer');
    }
}