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

        return json_encode([
            'error' => false, 
            'message' => 'done'
        ]);

        $this->form_validation->set_rules('txtname', 'Full Name', 'required');
        $this->form_validation->set_rules('slcgender', 'Gender', 'required');
        $this->form_validation->set_rules('slcdepartment', 'Department', 'required');
        $this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('txtmobile', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
       
        
        $name=$this->input->post('txtname');
        $gender=$this->input->post('slcgender');
        
        if($this->form_validation->run() !== false)
        {
            $this->load->library('image_lib');
            
            if($login>0)
            {
                $data = $this->Deduction_model->insert(array('id'=>$login,'staff_name'=>$name,'gender'=>$gender,'email'=>$email,'mobile'=>$mobile,'dob'=>$dob,'doj'=>$doj,'address'=>$address,'city'=>$city,'state'=>$state,'country'=>$country,'department_id'=>$department,'pic'=>$image,'added_by'=>$added));
            }
            
            if($data == true)
            {
                $this->session->set_flashdata('success', "Staff Deduction Added Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Something went wrong.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->index();
            return false;
        } 
    }

    public function update()
    {
        $this->load->helper('form');
        $this->form_validation->set_rules('txtname', 'Full Name', 'required');
        $this->form_validation->set_rules('slcgender', 'Gender', 'required');
        $this->form_validation->set_rules('slcdepartment', 'Department', 'required');
        $this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('txtmobile', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('txtdob', 'Date of Birth', 'required');
        $this->form_validation->set_rules('txtdoj', 'Date of Joining', 'required');
        $this->form_validation->set_rules('txtcity', 'City', 'required');
        $this->form_validation->set_rules('txtstate', 'State', 'required');
        $this->form_validation->set_rules('slccountry', 'Country', 'required');
        
        $id=$this->input->post('txtid');
        $name=$this->input->post('txtname');
        $gender=$this->input->post('slcgender');
        $department=$this->input->post('slcdepartment');
        $email=$this->input->post('txtemail');
        $mobile=$this->input->post('txtmobile');
        $dob=$this->input->post('txtdob');
        $doj=$this->input->post('txtdoj');
        $city=$this->input->post('txtcity');
        $state=$this->input->post('txtstate');
        $country=$this->input->post('slccountry');
        $address=$this->input->post('txtaddress');

        if($this->form_validation->run() !== false)
        {
            $this->load->library('image_lib');
            $config['upload_path']= 'uploads/profile-pic/';
            $config['allowed_types'] ='gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('filephoto'))
            {
                $data=$this->Staff_model->update_staff(array('staff_name'=>$name,'gender'=>$gender,'email'=>$email,'mobile'=>$mobile,'dob'=>$dob,'doj'=>$doj,'address'=>$address,'city'=>$city,'state'=>$state,'country'=>$country,'department_id'=>$department),$id);
            }
            else
            {
                $image_data =   $this->upload->data();

                $configer =  array(
                  'image_library'   => 'gd2',
                  'source_image'    =>  $image_data['full_path'],
                  'maintain_ratio'  =>  TRUE,
                  'width'           =>  150,
                  'height'          =>  150,
                  'quality'         =>  50
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();

                $data=$this->Staff_model->update_staff(array('staff_name'=>$name,'gender'=>$gender,'email'=>$email,'mobile'=>$mobile,'dob'=>$dob,'doj'=>$doj,'address'=>$address,'city'=>$city,'state'=>$state,'country'=>$country,'department_id'=>$department,'pic'=>$image_data['file_name'],'added_by'=>$added),$id);
            }
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Staff Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Staff Updated Failed.");
            }
            redirect(base_url()."manage-staff");
        }
        else{
            $this->index();
            return false;

        } 
    }


    function edit($id)
    {
        $data['department']=$this->Department_model->select_departments();
        $data['country']=$this->Home_model->select_countries();
        $data['content']=$this->Staff_model->select_staff_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/edit-staff',$data);
        $this->load->view('admin/footer');
    }


    function delete($id)
    {
        $this->Home_model->delete_login_byID($id);
        $data=$this->Staff_model->delete_staff($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Staff Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Staff Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    



}
