<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }


    public function manage($id)
    {
        $data['staff']= $this->Staff_model->select_staff_byID($id)[0];
        $data['deductions']= $this->Deduction_model
                                    ->getWhere(['staff_id' => $id]);
        

        $this->load->view('admin/header');
        $this->load->view('admin/manage-deduction', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {

        $this->form_validation->set_rules('amount', 'Amount', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');
       
        
        $amount=$this->input->post('amount');
        $staff_id=$this->input->post('staff_id');
        $date=$this->input->post('date');
        $reason=$this->input->post('reason');
        $status=$this->input->post('status') ;
        
        if($this->form_validation->run() !== false)
        {
            
            $data = $this->Deduction_model->insert(array( 'staff_id' => $staff_id,
                                                          'amount'=>$amount,
                                                          'date'=>$date,
                                                          'status'=>$status,
                                                          'reason'=>$reason));
            
            if($data)
            {
                $this->session->set_flashdata('success', "Staff Deduction Added Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Something went wrong.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->manage($staff_id);
            return false;
        }
    }

    public function update($id)
    {
        $this->load->helper('form');
       
        $this->form_validation->set_rules('amount', 'Amount', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');
       
        
        $amount=$this->input->post('amount');
        $staff_id=$this->input->post('staff_id');
        $date=$this->input->post('date');
        $reason=$this->input->post('reason');
        $status=$this->input->post('status');

        if($this->form_validation->run() !== false)
        {
         
           
         $data=$this->Deduction_model->update(array(
                    'amount'=>$amount,
                    'date'=>$date,
                    'status'=>$status,
                    'reason'=>$reason),
                    $id);
            
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Deduction Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Deduction Updated Failed.");
            }
            redirect(base_url(). "staff/manage-deductions/" . $staff_id);
        }
        else{
            $this->manage($staff_id);
            return false;
        } 
    }


    public function edit($id)
    {
        $data['deduction']= $this->Deduction_model
                                ->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/edit-deduction',$data);
        $this->load->view('admin/footer');
    }


    public function delete($id)
    {
        $this->Deduction_model->delete($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Deduction Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Deduction Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    



}
