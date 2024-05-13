<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudioIncome extends CI_Controller {

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
        $data['incomes'] = $this->Studio_Income_model->getAll();

        $this->load->view('admin/header');
        $this->load->view('admin/manage-studio-income', $data);
        $this->load->view('admin/footer');
    }




    public function insert()
    {
        $this->form_validation->set_rules('client', 'Client', 'required');
        $this->form_validation->set_rules('invoice_amount', 'Invoice Amount', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
       
        
        $client =   $this->input->post('client');
        $description    =   $this->input->post('description');
        $invoice_amount =   $this->input->post('invoice_amount');
        $amount_paid    =   $this->input->post('amount_paid');
        $date           =   $this->input->post('date');
        $status         =   $this->input->post('status') ?? 1;
        
        if($this->form_validation->run() !== false)
        {
            
            $data = $this->Studio_Income_model->insert([
                                                'client'    =>  $client,
                                                'description'     =>  $description,
                                                'invoice_amount'  =>  $invoice_amount,
                                                'amount_paid'     =>  $amount_paid,
                                                'date'      =>  $date,
                                                'status'    =>  $status
                                            ]);
            
            if($data)
            {
                $this->session->set_flashdata('success', "Income Added Succesfully");
            }else{
                $this->session->set_flashdata('error', "Something went wrong.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->session->set_flashdata('error', "Validation Error. Fill required fields.");
            $this->index();
            return false;
        }
    }

    public function update($id)
    {
        $this->load->helper('form');
       
        $this->form_validation->set_rules('client', 'Client', 'required');
        $this->form_validation->set_rules('invoice_amount', 'Invoice Amount', 'required');
        $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
       
        
        $client =   $this->input->post('client');
        $description    =   $this->input->post('description');
        $invoice_amount =   $this->input->post('invoice_amount');
        $amount_paid    =   $this->input->post('amount_paid');
        $date           =   $this->input->post('date');
        $status         =   $this->input->post('status') ?? 1;

        if($this->form_validation->run() !== false)
        {
                  
            $this->Studio_Income_model->update([
                'client'    =>  $client,
                'description'     =>  $description,
                'invoice_amount'  =>  $invoice_amount,
                'amount_paid'     =>  $amount_paid,
                'date'      =>  $date,
                'status'    =>  $status
            ], $id);

            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Income Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Income Updated Failed.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->session->set_flashdata('error', "Validation Error. Fill required fields.");
            $this->index();
            return false;
        }
    }


    


    public function delete($id)
    {
        $this->Studio_Income_model->delete($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Income Deleted Succesfully");
        }else{
            $this->session->set_flashdata('error', "Sorry, Income Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
