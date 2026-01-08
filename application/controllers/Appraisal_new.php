<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property $session
 * @property $Department_model
 * @property $Staff_model
 * @property $Appraisal_new_model
 */
class Appraisal_new extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
    }

    public function create($staff_id = null)
    {
        // Enforce: Only HOD/Admin can create, and must specify staff_id
        $role = $this->session->userdata('role');
        if (!in_array($role, ['hrm', 'super'])) {
             // For simplicity based on existing logic:
             // Logic in existing system checks if user is head of department. 
             // Let's assume for now strictly roles or manually checked.
             // If manual HOD check needed:
             $isHod = $this->check_is_hod();
             if(!$isHod && !in_array($role, ['hrm', 'super'])) {
                 $this->session->set_flashdata('error', 'Only HODs or HR can create appraisals.');
                 redirect('appraisal_new/my_appraisals');
             }
        }

        if (!$staff_id) {
             show_error('You must select a staff member to appraise.');
        }
        
        $data['staff'] = $this->Staff_model->select_staff_byID($staff_id)[0];
        $data['department'] = $this->Department_model->select_department_byID($data['staff']['department_id'])[0];
        
        // Default empty KPAs for the view to render rows
        $data['kpas'] = [1, 2, 3, 4]; 

        $this->load->view('admin/header');
        $this->load->view('admin/appraisal_new/create', $data);
        $this->load->view('admin/footer');
    }
    
    // Helper to check HOD status logic from existing controller
    private function check_is_hod() {
        $departments = $this->Department_model->select_departments();
        $loggedInUserId = $this->session->userdata('userid');
        if (isset($departments)) {
            foreach ($departments as $department) {
                if ($loggedInUserId == $department['staff_id']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function store()
    {
        $staff_id = $this->input->post('staff_id');
        
        $data = [
            'staff_id' => $staff_id,
            'department_id' => $this->input->post('department_id'),
            'position' => $this->input->post('position'),
            'month_under_review' => $this->input->post('month_under_review'),
            
            'rating_teamwork' => $this->input->post('rating_teamwork'),
            'comment_teamwork' => $this->input->post('comment_teamwork'),
            'rating_communication' => $this->input->post('rating_communication'),
            'comment_communication' => $this->input->post('comment_communication'),
            'rating_quality' => $this->input->post('rating_quality'),
            'comment_quality' => $this->input->post('comment_quality'),
            'rating_timeliness' => $this->input->post('rating_timeliness'),
            'comment_timeliness' => $this->input->post('comment_timeliness'),
            'rating_innovation' => $this->input->post('rating_innovation'),
            'comment_innovation' => $this->input->post('comment_innovation'),
            'rating_professionalism' => $this->input->post('rating_professionalism'),
            'comment_professionalism' => $this->input->post('comment_professionalism'),
            
            'tasks_assigned' => $this->input->post('tasks_assigned'),
            'tasks_completed' => $this->input->post('tasks_completed'),
            'completion_rate' => $this->input->post('completion_rate'),
            'accuracy_rate' => $this->input->post('accuracy_rate'),
            
            'strengths' => json_encode($this->input->post('strengths')),
            'weaknesses' => json_encode($this->input->post('weaknesses')),
            'training_needs' => json_encode($this->input->post('training_needs')),
            'next_month_goals' => json_encode($this->input->post('next_month_goals')),
            
            'hod_remarks' => $this->input->post('hod_remarks'), // Added HOD remarks on creation
            
            'status' => Appraisal_new_model::APPRAISAL_DRAFT // Default to draft
        ];

        // Process KPAs
        $kpas = $this->process_kpas_from_post();

        if ($this->Appraisal_new_model->create_appraisal($data, $kpas)) {
            $this->session->set_flashdata('success', 'Appraisal draft created. You can edit it or send to HR.');
            redirect('appraisal_new/list_staff_appraisals/' . $staff_id); 
        } else {
            $this->session->set_flashdata('error', 'Failed to create appraisal');
            redirect('appraisal_new/create/' . $staff_id);
        }
    }
    
    // Helper to extract KPAs
    private function process_kpas_from_post() {
        $kpas = [];
        $kpa_categories = $this->input->post('kpa_category');
        $kpa_descriptions = $this->input->post('kpa_description');
        $kpa_expected = $this->input->post('kpa_expected');
        $kpa_actual = $this->input->post('kpa_actual');
        $kpa_ratings = $this->input->post('kpa_rating');

        if ($kpa_categories) {
            for ($i = 0; $i < count($kpa_categories); $i++) {
                if (!empty($kpa_categories[$i])) {
                    $kpas[] = [
                        'category' => $kpa_categories[$i],
                        'description' => $kpa_descriptions[$i],
                        'expected_output' => $kpa_expected[$i],
                        'actual_output' => $kpa_actual[$i],
                        'rating' => $kpa_ratings[$i]
                    ];
                }
            }
        }
        return $kpas;
    }

    public function edit($id)
    {
        // Edit view for HOD/Admin (only if draft)
        $data['appraisal'] = $this->Appraisal_new_model->get_appraisal($id);
        if (!$data['appraisal']) {
            show_404();
        }
        
        // Ownership/Draft check
        if ($data['appraisal']['status'] != Appraisal_new_model::APPRAISAL_DRAFT) {
             $this->session->set_flashdata('error', 'Cannot edit appraisal that is not in draft mode.');
             redirect('appraisal_new/list_staff_appraisals/'.$data['appraisal']['staff_id']);
        }
        
        $data['staff'] = $this->Staff_model->select_staff_byID($data['appraisal']['staff_id'])[0];
        $data['department'] = $this->Department_model->select_department_byID($data['appraisal']['department_id'])[0];
        
        $this->load->view('admin/header');
        $this->load->view('admin/appraisal_new/edit', $data);
        $this->load->view('admin/footer');
    }

    public function update($id)
    {
        $appraisal = $this->Appraisal_new_model->get_appraisal($id);
        if($appraisal['status'] != Appraisal_new_model::APPRAISAL_DRAFT) {
             show_error('Cannot edit non-draft appraisal');
        }

        $data = [
            'rating_teamwork' => $this->input->post('rating_teamwork'),
            'comment_teamwork' => $this->input->post('comment_teamwork'),
            'rating_communication' => $this->input->post('rating_communication'),
            'comment_communication' => $this->input->post('comment_communication'),
            'rating_quality' => $this->input->post('rating_quality'),
            'comment_quality' => $this->input->post('comment_quality'),
            'rating_timeliness' => $this->input->post('rating_timeliness'),
            'comment_timeliness' => $this->input->post('comment_timeliness'),
            'rating_innovation' => $this->input->post('rating_innovation'),
            'comment_innovation' => $this->input->post('comment_innovation'),
            'rating_professionalism' => $this->input->post('rating_professionalism'),
            'comment_professionalism' => $this->input->post('comment_professionalism'),
            
            'tasks_assigned' => $this->input->post('tasks_assigned'),
            'tasks_completed' => $this->input->post('tasks_completed'),
            'completion_rate' => $this->input->post('completion_rate'),
            'accuracy_rate' => $this->input->post('accuracy_rate'),
            
            'strengths' => json_encode($this->input->post('strengths')),
            'weaknesses' => json_encode($this->input->post('weaknesses')),
            'training_needs' => json_encode($this->input->post('training_needs')),
            'next_month_goals' => json_encode($this->input->post('next_month_goals')),
            
            'hod_remarks' => $this->input->post('hod_remarks'),
        ];
        
        $kpas = $this->process_kpas_from_post();
        
        if ($this->Appraisal_new_model->update_appraisal($id, $data, $kpas)) {
            $this->session->set_flashdata('success', 'Appraisal updated successfully.');
            redirect('appraisal_new/list_staff_appraisals/' . $appraisal['staff_id']);
        } else {
             $this->session->set_flashdata('error', 'Update failed.');
             redirect('appraisal_new/edit/' . $id);
        }
    }
    
    public function submit_to_hr($id) {
        $appraisal = $this->Appraisal_new_model->get_appraisal($id);
        if($appraisal['status'] != Appraisal_new_model::APPRAISAL_DRAFT) {
             $this->session->set_flashdata('error', 'Only drafts can be submitted to HR.');
             redirect('appraisal_new/manage');
        }
        
        $this->Appraisal_new_model->update_appraisal($id, ['status' => Appraisal_new_model::APPRAISAL_PENDING]);
        $this->session->set_flashdata('success', 'Appraisal submitted to HR.');
        
        // Redirect back to list
        if(in_array($this->session->userdata('role'), ['hod', 'admin'])) {
             redirect('appraisal_new/list_staff_appraisals/'.$appraisal['staff_id']);
        } else {
             redirect('appraisal_new/manage');
        }
    }

    public function view($id)
    {
        $data['appraisal'] = $this->Appraisal_new_model->get_appraisal($id);
        if (!$data['appraisal']) {
            show_404();
        }
        
        $data['staff'] = $this->Staff_model->select_staff_byID($data['appraisal']['staff_id'])[0];
        $data['department'] = $this->Department_model->select_department_byID($data['appraisal']['department_id'])[0];
        
        $this->load->view('admin/header');

        // Logic to determine which view to load
        $current_user_id = $this->session->userdata('userid');
        if ($current_user_id == $data['appraisal']['staff_id']) {
             $this->load->view('admin/appraisal_new/user_view', $data);
        } else {
             $this->load->view('admin/appraisal_new/view', $data);
        }

        $this->load->view('admin/footer');
    }

    public function hr_approve($id) {
        if (!in_array($this->session->userdata('role'), ['hrm', 'super'])) {
             $this->session->set_flashdata('error', 'Access denied.');
             redirect('appraisal_new/manage');
        }

        $appraisal = $this->Appraisal_new_model->get_appraisal($id);

        $data = [
            'status' => Appraisal_new_model::APPRAISAL_HR_APPROVED,
            'hr_remarks' => $this->input->post('hr_remarks'),
            'hr_approval_date' => date('Y-m-d')
        ];
        
        $this->Appraisal_new_model->update_appraisal($id, $data);
        $this->session->set_flashdata('success', 'Appraisal approved. Waiting for staff response.');
        $user = $this->Staff_model->select_staff_byID($appraisal[0]['staff_id']);
        $this->send_email_notification($user[0]['email'], $user[0]['staff_name']);
        // Redirect back to list if referred from list, else view
        if (strpos($_SERVER['HTTP_REFERER'], 'view') !== false) {
             redirect('appraisal_new/view/'.$id);
        } else {
             redirect('appraisal_new/manage');
        }
    }

    public function staff_comment($id) {
        // Staff self-assessment
        $data = [
            'employee_remarks' => $this->input->post('employee_remarks'),
            'status' => Appraisal_new_model::APPRAISAL_STAFF_REPLIED
        ];
        $this->Appraisal_new_model->update_appraisal($id, $data);
        $this->session->set_flashdata('success', 'Remarks submitted.');
        redirect('appraisal_new/view/'.$id);
    }

    public function super_approve($id) {
         if ($this->session->userdata('role') != 'super') {
             $this->session->set_flashdata('error', 'Access denied.');
             redirect('appraisal_new/manage');
        }
        
        $data = [
            'status' => Appraisal_new_model::APPRAISAL_FINAL
        ];
        $this->Appraisal_new_model->update_appraisal($id, $data);
        $this->session->set_flashdata('success', 'Appraisal finalized.');
        
         if (strpos($_SERVER['HTTP_REFERER'], 'view') !== false) {
             redirect('appraisal_new/view/'.$id);
        } else {
             redirect('appraisal_new/manage');
        }
    }

    public function my_appraisals()
    {
        $staff_id = $this->session->userdata('staff_id'); // Assuming userid maps to staff_id
        // If retrieving by role 'staff', we pass the ID
        $data['appraisals'] = $this->Appraisal_new_model->getWhere(['staff_id' => $staff_id, "status IN ('hr_approved', 'staff_replied', 'final')"]);
        
        $this->load->view('admin/header');
        $this->load->view('admin/appraisal_new/list', $data);
        $this->load->view('admin/footer');
    }

    public function manage()
    {
        if($this->session->userdata('role') == 'staff' && !$this->check_is_hod()) {
            $this->session->set_flashdata('error', 'Access denied.');
            redirect('appraisal_new/my_appraisals');
        }

        $data['departments'] = $this->Department_model->select_departments();
        $data['staff_members'] = $this->Staff_model->get_all_staffs();
         
        // Helper for department names
        $dept_map = [];
        if($data['departments']) {
            foreach($data['departments'] as $d) {
                $dept_map[$d['id']] = $d['department_name'];
            }
        }
        $data['department_names'] = $dept_map;

        $this->load->view('admin/header');
        $this->load->view('admin/appraisal_new/manage', $data);
        $this->load->view('admin/footer');
    }
    
    public function list_staff_appraisals($staff_id)
    {
        // Lists appraisals for a specific staff member (for HOD/Admin view)
        $data['appraisals'] = $this->Appraisal_new_model->list_appraisals($staff_id);
        $data['staff_id'] = $staff_id;
        $data['hod'] = false;
        
        $staff = $this->Staff_model->select_staff_byID($staff_id);
        if($staff) {
            $data['staff_name'] = $staff[0]['staff_name'];
        }

        if ($this->session->userdata('role') == 'staff') {
			// Fetch the logged-in user's department ID
			$loggedInUserId = $this->session->userdata('userid');
			$loggedInUser = $this->Staff_model->select_staff_byID($loggedInUserId)[0];
			$loggedInUserDepartmentId = $loggedInUser['department_id'];

			// Fetch the department ID of the user whose appraisal is being viewed
			$user = $this->Staff_model->select_staff_byID($staff_id)[0];
			$userDepartmentId = $user['department_id'];

			// Check if the logged-in user's department ID matches the user being viewed
			if ($loggedInUserDepartmentId == $userDepartmentId) {
				$data['hod'] = true;
			}
		}
        
        $this->load->view('admin/header');
        $this->load->view('admin/appraisal_new/list', $data); // Reusing list view
        $this->load->view('admin/footer');
    }

    public function unapproved_appraisal()
	{
		$data['appraisals'] = $this->Appraisal_new_model->getWhere(["status" => $this->Appraisal_new_model::APPRAISAL_STAFF_REPLIED]);

		$this->load->view('admin/header');
		$this->load->view('admin/unapproved-appraisal', $data);
		$this->load->view('admin/footer');
	}

    private function send_email_notification($employee_email, $employee_name)
	{
		// Load the email library
		$this->load->library('email');

		// Configure email settings
		$config['protocol'] = $this->config->item('protocol');
		$config['smtp_host'] = $this->config->item('smtp_host');
		$config['smtp_port'] = $this->config->item('smtp_port');
		$config['smtp_crypto'] = $this->config->item('smtp_crypto');
		$config['smtp_user'] = $this->config->item('smtp_user');
		$config['smtp_pass'] = $this->config->item('smtp_pass');
		$config['mailtype'] = $this->config->item('mailtype');
		$config['charset'] = $this->config->item('charset');
		$config['newline'] = $this->config->item('newline');

		$this->email->initialize($config);

		// Email content
		$this->email->from('support@bloomdigitmedia.com', 'Bloom EMS');
		$this->email->to($employee_email);
		$this->email->subject("Appraisal Available");
		$this->email->message("Dear $employee_name, your appraisal is now available for self appraisal visit your hr portal for that.");

		// Send email
		$this->email->send();
	}
}
