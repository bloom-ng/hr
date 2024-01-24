<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

    // public function index()
    // {
    //     $this->load->view('admin/header');
    //     $this->load->view('admin/add-department');
    //     $this->load->view('admin/footer');
    // }

    // public function manage_department()
    // {
    //     $data['content']=$this->Department_model->select_departments();
    //     $this->load->view('admin/header');
    //     $this->load->view('admin/manage-department',$data);
    //     $this->load->view('admin/footer');
    // }

    public function insert()
    {
        $time_in = $this->input->post('time_in');
        $time_out = $this->input->post('time_out');
        $staff_id = $this->input->post('staff_id');
        $notes = $this->input->post('notes');
        $date = $this->input->post('date');

        // Check if attendance record already exists for the given staff and date
        $existing_record = $this->Attendance_model->get_attendance_by_staff_and_date($staff_id, $date);

        if ($existing_record) {
            // If attendance record exists, update it
            $update_data = array(
                'time_in' => $time_in,
                'time_out' => $time_out,
                'notes' => $notes,
            );

            $this->Attendance_model->update_attendance($existing_record['id'], $update_data);

            // $this->session->set_flashdata('success', "Attendance Updated Successfully");
        } else {
            // If attendance record doesn't exist, insert a new one
            $insert_data = array(
                'staff_id' => $staff_id,
                'date' => $date,
                'time_in' => $time_in,
                'time_out' => $time_out,
                'notes' => $notes,
            );

            $this->Attendance_model->insert_attendance($insert_data);

            // $this->session->set_flashdata('success', "Attendance Inserted Successfully");
            redirect($_SERVER['HTTP_REFERER']);
            redirect(base_url()."/manage-staff");
        }

    }

    public function update()
    {
        $id=$this->input->post('txtid');
        $department=$this->input->post('txtdepartment');
        $data=$this->Department_model->update_department(array('department_name'=>$department),$id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Department Updated Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Department Update Failed.");
        }
        redirect(base_url()."department/manage_department");
    }


    function edit($id)
    {
        $data['content']=$this->Department_model->select_department_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/edit-department',$data);
        $this->load->view('admin/footer');
    }


    function delete($id)
    {
        $data=$this->Department_model->delete_department($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Department Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Department Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }



}
