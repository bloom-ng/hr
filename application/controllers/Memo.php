<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memo extends CI_Controller {

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
        $data['memos'] = $this->Memo_model->getAll();

        $this->load->view('admin/header');
        $this->load->view('admin/manage-memo', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/add-memo', []);
        $this->load->view('admin/footer');
    }



    public function insert()
    {

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
       
        
        $title=$this->input->post('title');
        $body=$this->input->post('body');
        $date=$this->input->post('date');
        $status=$this->input->post('status') ?? 0;
        
        if($this->form_validation->run() !== false)
        {
            
            $data = $this->Memo_model->insert(['title'=>$title,
                                                'body'=>$body,
                                                'date'=>$date,
                                                'status'=>$status]
                                                        );
            
            if($data)
            {
                $this->session->set_flashdata('success', "Memo Added Succesfully");
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
       
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
       
        
        $title=$this->input->post('title');
        $body=$this->input->post('body');
        $date=$this->input->post('date');
        $status=$this->input->post('status') ?? 0;

        if($this->form_validation->run() !== false)
        {
                  
            $this->Memo_model->update(['title'=>$title,
                                                'body'=>$body,
                                                'date'=>$date,
                                                'status'=>$status], $id);
            
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Memo Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Memo Updated Failed.");
            }
            redirect(base_url(). "memos");
        }
        else{
            $this->index();
            return false;
        }
    }


    public function edit($id)
    {
        $data['memo']= $this->Memo_model
                                ->get($id)[0];

        $this->load->view('admin/header');
        $this->load->view('admin/edit-memo', $data);
        $this->load->view('admin/footer');
    }


    public function delete($id)
    {
        $this->Memo_model->delete($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Memo Deleted Succesfully");
        }else{
            $this->session->set_flashdata('error', "Sorry, Memo Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
