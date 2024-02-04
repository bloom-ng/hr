<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

    public function export()
    {
        $this->load->model('Attendance_model');
        $this->load->view('admin/header');
        $this->load->view('admin/export_attendance');
        $this->load->view('admin/footer');
    }

    // Add a method to handle the export request
    // public function do_export()
    // {
    //     // Get the submitted from_date and to_date
    //     $fromDate = $this->input->post('from_date');
    //     $toDate = $this->input->post('to_date');

    //     // Use the provided date range to export attendance data
    //     // Modify this according to your export logic
    //     $data['attendanceData'] = $this->Attendance_model->export_attendance_by_date_range($fromDate, $toDate);

    //     // Load a view to display the exported data (you can customize this view)
    //     // $this->load->view('exported_attendance', $data);
    //     echo json_encode($attendanceData);
    // }

    public function fetch_attendance()
    {
        $fromDate = $this->input->post('from_date');
        $toDate = $this->input->post('to_date');

        $attendanceData = $this->Attendance_model->get_attendance_by_date_range($fromDate, $toDate);

        echo json_encode($attendanceData);
    }

    public function check_existing_attendance() {
        $staffId = $this->input->post('staff_id');
        $selectedDate = $this->input->post('date');

        $existingAttendance = $this->Attendance_model->get_attendance_by_staff_and_date($staffId, $selectedDate);

        echo json_encode($existingAttendance);
    }

    public function manage_attendance()
    {
        $data['staff_members']=$this->Staff_model->get_all_staffs();
        $this->load->view('admin/header');
        $this->load->view('admin/manage-attendance', $data);
        $this->load->view('admin/footer');
        return;

    }

    public function check_attendance()
    {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $staff_id = $this->input->post('staff_id');

        $attendance_result = $this->Attendance_model->check_attendance($staff_id, $from_date, $to_date);

        $data['attendance_result'] = $attendance_result;
        $attendance_result_html = $this->load->view('admin/attendance_result', $data, TRUE);

        echo $attendance_result_html;
    }



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

            // // $this->session->set_flashdata('success', "Attendance Inserted Successfully");
            // redirect($_SERVER['HTTP_REFERER']);
            // redirect(base_url()."/manage-staff");
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
