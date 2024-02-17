<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property $session
 * @property $Department_model
 * @property $Staff_model
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

	public function save() {
        // Assuming you have form validation rules set up
        
        // Get form data
        $data = array(
			'name' => $this->input->post('name'),
			'staff_id' => $this->input->post('staff_id'),
			'job_title' => $this->input->post('job_title'),
			'department_id' => $this->input->post('department_id'),
			'department_name' => $this->input->post('department_name'),
			'date' => $this->input->post('date'),
			'created_by' => $this->session->userdata('id'), // Get logged-in user's ID
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
			'status' => 'pending',
		);


        // Load the model to interact with the database
        $this->load->model('Appraisal_model');

        // Call the model function to save the data
        $result = $this->Appraisal_model->save_appraisal($data);

        if ($result) {
            redirect(base_url()."manage-appraisal");
        } else {
            // Error handling
            // Redirect or show error message
        }
    }
}
