<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission extends CI_Controller {

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
        $data['staff_members']=$this->Staff_model->get_all_staffs();
        $this->load->view('admin/header');
        $this->load->view('admin/list-commission-staff', $data);
        $this->load->view('admin/footer');
        return;

    }


    public function manage($id)
    {
        $data['staff']= $this->Staff_model->select_staff_byID($id)[0];
        $data['commissions']= $this->Commission_model
                                    ->getWhere(['staff_id' => $id]);
        

        $this->load->view('admin/header');
        $this->load->view('admin/manage-commission', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {

        $this->form_validation->set_rules('total', 'Total Value', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('commission', 'Commission', 'required');
        $this->form_validation->set_rules('client', 'Client', 'required');
       
        
        $total=$this->input->post('total');
        $staff_id=$this->input->post('staff_id');
        $date=$this->input->post('date');
        $client=$this->input->post('client');
        $commission=$this->input->post('commission');
        $status=$this->input->post('status') ?? 0;
        $comments=$this->input->post('comments') ?? "";
        
        if($this->form_validation->run() !== false)
        {
            
            $data = $this->Commission_model->insert(array( 'staff_id' => $staff_id,
                                                          'client'=>$client,
                                                          'total'=>$total,
                                                          'commission'=>$commission,
                                                          'date'=>$date,
                                                          'status'=>$status,
                                                          'comments'=>$comments
                                                          )
                                                        );
            
            if($data)
            {
                $this->session->set_flashdata('success', "Staff Commission Added Succesfully");
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
       
        $this->form_validation->set_rules('total', 'Total Value', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('commission', 'Commission', 'required');
        $this->form_validation->set_rules('client', 'Client', 'required');
       
        
        $total=$this->input->post('total');
        $staff_id=$this->input->post('staff_id');
        $date=$this->input->post('date');
        $client=$this->input->post('client');
        $commission=$this->input->post('commission');
        $status=$this->input->post('status') ?? 0;
        $comments=$this->input->post('comments') ?? "";

        if($this->form_validation->run() !== false)
        {
         
           
         $data=$this->Commission_model->update(array(
                    'staff_id'=>$staff_id,
                    'client'=>$client,
                    'total'=>$total,
                    'commission'=>$commission,
                    'date'=>$date,
                    'status'=>$status,
                    'comments'=>$comments
                    ),
                    $id);
            
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Commission Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Commission Updated Failed.");
            }
            redirect(base_url(). "commission/manage/" . $staff_id);
        }
        else{
            $this->manage($staff_id);
            return false;
        } 
    }


    public function edit($id)
    {
        $data['commission']= $this->Commission_model
                                ->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/edit-commission',$data);
        $this->load->view('admin/footer');
    }


    public function delete($id)
    {
        $this->Commission_model->delete($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Commission Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Commission Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
