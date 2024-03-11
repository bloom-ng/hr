<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
        $data['user'] = $this->User_model->get($this->session->userdata('userid'))[0];



        $this->load->view('admin/header');
        $this->load->view('admin/profile', $data);
        $this->load->view('admin/footer');
    }

    public function update($id)
    {
        $this->load->helper('form');
       
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('role', 'Role', 'required');
       
        
        $username=$this->input->post('username');
        $role=$this->input->post('role');
        $password=$this->input->post('password');
        
        if($this->form_validation->run() !== false)
        {
         
            $user_data = [
                'username' => $username,
                'role'=>$role
            ];
            if (!empty($password)) {
                $user_data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            $this->User_model->update($user_data, $id);
            
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Admin Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Admin Updated Failed.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->index();
            return false;
        }
    }




    public function delete($id)
    {
        $this->User_model->delete_login_byID($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Admin Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Admin Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function reset_password($id)
    {
        $user_data['password'] = password_hash("password", PASSWORD_DEFAULT);
        
        $this->User_model->update($user_data, $id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Admin Password Reset Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Something went wrong.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    



}
