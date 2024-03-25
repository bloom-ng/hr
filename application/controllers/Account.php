<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in') && 
            ! (in_array($this->session->userdata('role'), ["super", "finance"])) )
        {
            redirect(base_url().'login');
        }
    }

    public function index()
    {
        $data['staff_members']=$this->Staff_model->get_all_staffs();

        $this->load->view('admin/header');
        $this->load->view('admin/manage-account', $data);
        $this->load->view('admin/footer');

    }



    public function update($id)
    {
        $this->load->helper('form');
       

       
        
        $base_salary = $this->input->post('base_salary') ?? 0;
        $bank = $this->input->post('bank') ?? "";
        $account = $this->input->post('account') ?? "";

       
           
         $payload = [
            'base_salary' => $base_salary,
            'account' => $account,
            'bank' => $bank
            ];
         $data = $this->Staff_model->update_staff($payload, $id);
            
            
            if($this->db->affected_rows() > 0)
            {
                $this->Log_model->log('update-account', $this->session->userdata('userid'), "Updated Staff $id account details", $payload);
                $this->session->set_flashdata('success', "Account Updated Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Account Updated Failed.");
            }
            redirect($_SERVER['HTTP_REFERER']);
       
    }




 
}
