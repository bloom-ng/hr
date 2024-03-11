<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property $session
 * @property $Department_model
 * @property $Staff_model
 * @property $Appraisal_model
 */
class Appraisal extends CI_Controller {


	function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

	public function manage()
	{
		$departments = $this->Department_model->select_departments();

		$loggedInUserId = $this->session->userdata('userid');
		$isHod = false;

		if (isset($departments)) {
			foreach ($departments as $department) {
				if ($loggedInUserId == $department['staff_id'] || in_array($this->session->userdata('role'), array("hrm", "super"))) {
					// User is an HoD or has HRM/Super role
					$isHod = true;
					break;
				}
			}
		}

		if (!$isHod) {
			redirect('/'); // Assuming 'dashboard' is the route to your dashboard page
		}


		$data['staff_members']=$this->Staff_model->get_all_staffs();
		$data['departments']=$this->Department_model->select_departments();
		$this->load->view("admin/header");
		$this->load->view("admin/manage-appraisal", $data);
		$this->load->view("admin/footer");
	}

	public function add($id)
	{
		$data['staff']= $this->Staff_model->select_staff_byID($id)[0];
		$data['department'] = $this->Department_model->select_department_byID($data['staff']['department_id'])[0];
		
		$this->load->view('admin/header');
		$this->load->view('admin/add-appraisal', $data);
		$this->load->view('admin/footer');
	}

	public function edit($id)
	{
		$this->load->model('Appraisal_model');
		$data['appraisal'] = $this->Appraisal_model->get($id)[0];

		$this->load->view('admin/header');
		$this->load->view('admin/edit-appraisal', $data);
		$this->load->view('admin/footer');
	}

	public function list_appraisal($id)
	{
		$this->load->model('Appraisal_model');
		$data['appraisals'] = $this->Appraisal_model->list_appraisals($id);
		$data['hod'] = false;
		$data['staff'] = $this->Staff_model->select_staff_byID($id)[0];

		if ($this->session->userdata('role') == 'staff') {
			// Fetch the logged-in user's department ID
			$loggedInUserId = $this->session->userdata('userid');
			$loggedInUser = $this->Staff_model->select_staff_byID($loggedInUserId)[0];
			$loggedInUserDepartmentId = $loggedInUser['department_id'];

			// Fetch the department ID of the user whose appraisal is being viewed
			$user = $this->Staff_model->select_staff_byID($id)[0];
			$userDepartmentId = $user['department_id'];

			// Check if the logged-in user's department ID matches the user being viewed
			if ($loggedInUserDepartmentId == $userDepartmentId) {
				$data['hod'] = true;
			}
		}

		$this->load->view('admin/header');
		$this->load->view('admin/appraisal', $data);
		$this->load->view('admin/footer');

	}

	public function review_appraisal($id)
	{
		$this->load->model('Appraisal_model');
		$appraisal = $this->Appraisal_model->get($id);

		if ($appraisal){
			$data = $this->Appraisal_model->update(array('status' => $this->Appraisal_model::APPRAISAL_REVIEW), $id);

			if($this->db->affected_rows() > 0)
			{
				$this->session->set_flashdata('success', "Appraisal Succesfully Sent For Review");
			}else{
				$this->session->set_flashdata('error', "Sorry, Unable To Send Appraisal For Review");
			}
			redirect(base_url(). "manage-appraisal");
		} else {
			$this->session->set_flashdata('error', "Sorry, Unable To Find Appraisal");
			redirect(base_url(). "manage-appraisal");

		}

	}

	public function approve_appraisal($id)
	{
		$this->load->model('Appraisal_model');
		$appraisal = $this->Appraisal_model->get($id);

		if ($appraisal){
			$data = $this->Appraisal_model->update(array('status' => $this->Appraisal_model::APPRAISAL_APPROVED), $id);

			if($this->db->affected_rows() > 0)
			{
				$this->session->set_flashdata('success', "Appraisal Succesfully Approved");
			}else{
				$this->session->set_flashdata('error', "Sorry, Unable To Approve Appraisal");
			}
			redirect(base_url(). "manage-appraisal");
		} else {
			$this->session->set_flashdata('error', "Sorry, Unable To Find Appraisal");
			redirect(base_url(). "manage-appraisal");

		}

	}

	public function my_appraisal()
	{
		$this->load->model('Appraisal_model');
		$user = $this->session->userdata('staff_id');

//		$data['appraisal'] = $this->Appraisal_model->getWhere(array("staff_id" => $user, "status" => Appraisal_model::APPRAISAL_APPROVED || Appraisal_model::APPRAISAL_DONE));
		$data['appraisal'] = $this->Appraisal_model->getWhere(["staff_id" => $user, "status" => $this->Appraisal_model::APPRAISAL_APPROVED]);
		$this->load->view('admin/header');
		$this->load->view('staff/manage-appraisal', $data);
		$this->load->view('admin/footer');
	}

	public function self_appraisal($id)
	{
		$this->load->model('Appraisal_model');
		$user = $this->session->userdata('userid');

		$data = array(
			'employee_self_assessment' => $this->input->post('employee_self_assessment'),
			'status' => Appraisal_model::APPRAISAL_DONE,
		);

		$result = $this->Appraisal_model->update($data, $id);

		if ($result) {

			$this->session->set_flashdata('success', "Self Appraisal Succesfully");

			redirect(base_url()."my-appraisal");
		} else {
			$this->session->set_flashdata('error', "Sorry, Unable To Complete Self Appraisal");
		}
	}
	public function check_appraisal($id)
	{
		$this->load->model('Appraisal_model');
		$user = $this->session->userdata('staff_id');

		$data['appraisal'] = $this->Appraisal_model->get($id)[0];

		$this->load->view('admin/header');
		$this->load->view('staff/my-appraisal', $data);
		$this->load->view('admin/footer');
	}

	public function save() {
		// Load the model to interact with the database
		$this->load->model('Appraisal_model');
        // Assuming you have form validation rules set up

        // Get form data
        $data = array(
			'name' => $this->input->post('name'),
			'staff_id' => $this->input->post('staff_id'),
			'job_title' => $this->input->post('job_title'),
			'department_id' => $this->input->post('department_id'),
			'department_name' => $this->input->post('department_name'),
			'date' => $this->input->post('date'),
			'created_by' => $this->session->userdata('userid'), // Get logged-in user's ID
			'performance' => $this->input->post('overall_performance'),
			'performance_comment' => $this->input->post('overall_performance_comment'),
			'skills' => $this->input->post('job_knowledge'),
			'skills_comment' => $this->input->post('job_knowledge_comment'),
			'quality' => $this->input->post('quality_of_work'),
			'quality_comment' => $this->input->post('quality_of_work_comment'),
			'communication' => $this->input->post('communication_skills'),
			'communication_comment' => $this->input->post('communication_skills_comment'),
			'teamwork' => $this->input->post('teamwork_collaboration'),
			'teamwork_comment' => $this->input->post('teamwork_collaboration_comment'),
			'goals' => $this->input->post('achievement_of_goals'),
			'goals_comment' => $this->input->post('achievement_of_goals_reason'),
			'projects_assigned' => $this->input->post('assigned_projects_count'),
			'projects_completed' => $this->input->post('completed_projects_count'),
			'overall_performance' => $this->input->post('overall_performance'),
			'overall_performance_comment' => $this->input->post('overall_performance_comment'),
			'job_knowledge' => $this->input->post('job_knowledge'),
			'job_knowledge_comment' => $this->input->post('job_knowledge_comment'),
			'quality_of_work' => $this->input->post('quality_of_work'),
			'quality_of_work_comment' => $this->input->post('quality_of_work_comment'),
			'communication_skills' => $this->input->post('communication_skills'),
			'communication_skills_comment' => $this->input->post('communication_skills_comment'),
			'teamwork_collaboration' => $this->input->post('teamwork_collaboration'),
			'teamwork_collaboration_comment' => $this->input->post('teamwork_collaboration_comment'),
			'achievement_of_goals' => $this->input->post('achievement_of_goals'),
			'achievement_of_goals_reason' => $this->input->post('achievement_of_goals_reason'),
			'assigned_projects_count' => $this->input->post('assigned_projects_count'),
			'completed_projects_count' => $this->input->post('completed_projects_count'),
			'completion_of_projects_outcome' => $this->input->post('completion_of_projects_outcome'),
			'completion_of_projects_reason' => $this->input->post('completion_of_projects_reason'),
			'outstanding_job_knowledge' => $this->input->post('outstanding_job_knowledge') == 'true' ? true : false,
			'effective_communication' => $this->input->post('effective_communication') == 'true' ? true : false,
			'strong_team_player' => $this->input->post('strong_team_player') == 'true' ? true : false,
			'innovative_thinking' => $this->input->post('innovative_thinking') == 'true' ? true : false,
			'adaptable_to_change' => $this->input->post('adaptable_to_change') == 'true' ? true : false,
			'time_management' => $this->input->post('time_management') == 'true' ? true : false,
			'conflict_resolution' => $this->input->post('conflict_resolution') == 'true' ? true : false,
			'technical_skills_enhancement' => $this->input->post('technical_skills_enhancement') == 'true' ? true : false,
			'goal_setting_and_achievement' => $this->input->post('goal_setting_and_achievement') == 'true' ? true : false,
			'communication_with_team_members' => $this->input->post('communication_with_team_members') == 'true' ? true : false,
			'leadership_training' => $this->input->post('leadership_training') == 'true' ? true : false,
			'technical_skills_training' => $this->input->post('technical_skills_training') == 'true' ? true : false,
			'communication_skills_workshop' => $this->input->post('communication_skills_workshop') == 'true' ? true : false,
			'project_management_training' => $this->input->post('project_management_training') == 'true' ? true : false,
			'additional_comments' => $this->input->post('additional_comments'),
			'employee_self_assessment' => $this->input->post('employee_self_assessment'),
			'manager_comments' => $this->input->post('manager_comments'),
			'action_plan_for_improvement' => $this->input->post('action_plan_for_improvement'),
			'follow_up_meeting_schedule' => $this->input->post('follow_up_meeting_schedule'),
			'status' => $this->Appraisal_model::APPRAISAL_PENDING,
		);

        // Call the model function to save the data
        $result = $this->Appraisal_model->save_appraisal($data);

        if ($result) {

			$this->session->set_flashdata('success', "Appraisal Succesfully Added");

            redirect(base_url()."manage-appraisal");
        } else {
			$this->session->set_flashdata('error', "Sorry, Unable To Create Appraisal");
        }
    }

	public function update($id) {
		// Load the model to interact with the database
		$this->load->model('Appraisal_model');
		// Assuming you have form validation rules set up

		// Get form data
		$data = array(
			'name' => $this->input->post('name'),
			'staff_id' => $this->input->post('staff_id'),
			'job_title' => $this->input->post('job_title'),
			'department_id' => $this->input->post('department_id'),
			'department_name' => $this->input->post('department_name'),
			'date' => $this->input->post('date'),
			'created_by' => $this->session->userdata('userid'), // Get logged-in user's ID
			'performance' => $this->input->post('overall_performance'),
			'performance_comment' => $this->input->post('overall_performance_comment'),
			'skills' => $this->input->post('job_knowledge'),
			'skills_comment' => $this->input->post('job_knowledge_comment'),
			'quality' => $this->input->post('quality_of_work'),
			'quality_comment' => $this->input->post('quality_of_work_comment'),
			'communication' => $this->input->post('communication_skills'),
			'communication_comment' => $this->input->post('communication_skills_comment'),
			'teamwork' => $this->input->post('teamwork_collaboration'),
			'teamwork_comment' => $this->input->post('teamwork_collaboration_comment'),
			'goals' => $this->input->post('achievement_of_goals'),
			'goals_comment' => $this->input->post('achievement_of_goals_reason'),
			'projects_assigned' => $this->input->post('assigned_projects_count'),
			'projects_completed' => $this->input->post('completed_projects_count'),
			'overall_performance' => $this->input->post('overall_performance'),
			'overall_performance_comment' => $this->input->post('overall_performance_comment'),
			'job_knowledge' => $this->input->post('job_knowledge'),
			'job_knowledge_comment' => $this->input->post('job_knowledge_comment'),
			'quality_of_work' => $this->input->post('quality_of_work'),
			'quality_of_work_comment' => $this->input->post('quality_of_work_comment'),
			'communication_skills' => $this->input->post('communication_skills'),
			'communication_skills_comment' => $this->input->post('communication_skills_comment'),
			'teamwork_collaboration' => $this->input->post('teamwork_collaboration'),
			'teamwork_collaboration_comment' => $this->input->post('teamwork_collaboration_comment'),
			'achievement_of_goals' => $this->input->post('achievement_of_goals'),
			'achievement_of_goals_reason' => $this->input->post('achievement_of_goals_reason'),
			'assigned_projects_count' => $this->input->post('assigned_projects_count'),
			'completed_projects_count' => $this->input->post('completed_projects_count'),
			'completion_of_projects_outcome' => $this->input->post('completion_of_projects_outcome'),
			'completion_of_projects_reason' => $this->input->post('completion_of_projects_reason'),
			'outstanding_job_knowledge' => $this->input->post('outstanding_job_knowledge') == 'true' ? true : false,
			'effective_communication' => $this->input->post('effective_communication') == 'true' ? true : false,
			'strong_team_player' => $this->input->post('strong_team_player') == 'true' ? true : false,
			'innovative_thinking' => $this->input->post('innovative_thinking') == 'true' ? true : false,
			'adaptable_to_change' => $this->input->post('adaptable_to_change') == 'true' ? true : false,
			'time_management' => $this->input->post('time_management') == 'true' ? true : false,
			'conflict_resolution' => $this->input->post('conflict_resolution') == 'true' ? true : false,
			'technical_skills_enhancement' => $this->input->post('technical_skills_enhancement') == 'true' ? true : false,
			'goal_setting_and_achievement' => $this->input->post('goal_setting_and_achievement') == 'true' ? true : false,
			'communication_with_team_members' => $this->input->post('communication_with_team_members') == 'true' ? true : false,
			'leadership_training' => $this->input->post('leadership_training') == 'true' ? true : false,
			'technical_skills_training' => $this->input->post('technical_skills_training') == 'true' ? true : false,
			'communication_skills_workshop' => $this->input->post('communication_skills_workshop') == 'true' ? true : false,
			'project_management_training' => $this->input->post('project_management_training') == 'true' ? true : false,
			'additional_comments' => $this->input->post('additional_comments'),
			'employee_self_assessment' => $this->input->post('employee_self_assessment'),
			'manager_comments' => $this->input->post('manager_comments'),
			'action_plan_for_improvement' => $this->input->post('action_plan_for_improvement'),
			'follow_up_meeting_schedule' => $this->input->post('follow_up_meeting_schedule'),
			'status' => $this->Appraisal_model::APPRAISAL_PENDING,
		);

		// Call the model function to save the data
		$result = $this->Appraisal_model->update($data, $id);

		if ($result) {

			$this->session->set_flashdata('success', "Appraisal Succesfully Edited");

			redirect(base_url()."manage-appraisal");
		} else {
			$this->session->set_flashdata('error', "Sorry, Unable To Edit Appraisal");
		}
	}

}
