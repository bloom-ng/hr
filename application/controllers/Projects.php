<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Projects extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        // Check if user is logged in
        if (!$this->session->userdata('userid')) {
            redirect('/login');
        }

        $this->staff_id = $this->session->userdata('userid');
        $this->role = $this->session->userdata('role');
    }

    public function index()
    {
        $data = [];

        // Check user role and get appropriate projects
        if ($this->role === 'finance' || $this->role === 'hrm' || $this->role === 'super') {
            $data['projects'] = $this->Project_model->get();
        } else {
            // Regular staff - show projects from their department
            $staff = $this->Staff_model->select_staff_byID($this->staff_id);
            if ($staff && !empty($staff)) {
                $data['projects'] = $this->Project_model->get_by_department($staff[0]['department_id']);
            } else {
                $data['projects'] = [];
            }
        }

        $data['title'] = 'Projects';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/projects/index', $data);
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {
        $project = $this->Project_model->get($id);

        if (!$project) {
            show_404();
        }

        // Check permissions
        $staff = $this->Staff_model->select_staff_byID($this->staff_id);
        $is_manager = ($project->manager_id == $this->staff_id);
        $is_same_department = false;
        $user_role = $this->session->userdata('role');

        // Only check department if user is a staff member (not super/hrm)
        if (!in_array($user_role, ['super', 'hrm', 'finance']) && $staff && !empty($staff)) {
            $staff = $staff[0]; // Get first result
            $is_same_department = ($project->department_id == $staff['department_id']);
        }

        // Allow admin, HR, manager of the project, or HOD of the department
        if ($user_role !== 'super' && $user_role !== 'hrm' && !$is_manager && !$is_same_department) {
            $this->session->set_flashdata('error', 'You do not have permission to edit this project');
            redirect('projects');
        }

        // Prepare data for the form
        $is_admin = in_array($this->role, ['super', 'hrm', 'finance']);
        $data = [];
        $data['departments'] = $is_admin ? $this->Department_model->select_departments() : [];
        $data['staff_department'] = $staff['department_id'] ?? null;
        $data['is_admin'] = $is_admin;
        $data['is_hod'] = $this->Department_model->is_head_of_department($this->staff_id);

        // Get staff for manager selection based on role
        if ($is_admin) {
            // Super/HRM can select any staff as manager
            $data['staff_options'] = $this->Staff_model->select_staff();
        } else {
            // HOD can only select from their department
            $data['staff_options'] = $this->Staff_model->select_staff_byDept($data['staff_department']);
        }

        if ($_POST) {
            $this->form_validation->set_rules('name', 'Project Name', 'required');
            $this->form_validation->set_rules('client_email', 'Client Email', 'required|valid_email');
            $this->form_validation->set_rules('client_phone', 'Client Phone', 'required');
            $this->form_validation->set_rules('description_of_deliverables', 'Description of Deliverables', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high]');
            $this->form_validation->set_rules('schedule_type', 'Schedule Type', 'required|in_list[monthly,annual,quarterly,one-off]');
            $this->form_validation->set_rules('manager_id', 'Project Manager', 'required');

            if ($this->form_validation->run() === TRUE) {
                $original_data = (array) $project;
                $update_data = [];

                // Only allow certain fields to be updated based on user role
                $updatable_fields = [
                    'name',
                    'client_email',
                    'client_phone',
                    'description_of_deliverables',
                    'special_request',
                    'priority',
                    'schedule_type',
                    'schedule_date',
                    'manager_id'
                ];

                // Only admin/hrm can update status and department
                if (in_array($this->role, ['super', 'hrm'])) {
                    $updatable_fields[] = 'status';
                    $updatable_fields[] = 'department_id';
                } else {
                    // For non-admin users, preserve the current status
                    $update_data['status'] = $project->status;
                }

                // Only finance can update payment status and receipt ID
                if (in_array($this->role, ['super', 'finance'])) {
                    $updatable_fields[] = 'payment_status';
                    $updatable_fields[] = 'receipt_id';
                }

                // Only include fields that are in the updatable fields list
                foreach ($updatable_fields as $field) {
                    if ($this->input->post($field) !== null) {
                        $update_data[$field] = $this->input->post($field, TRUE);
                    }
                }

                $result = $this->Project_model->update($id, $update_data, $project);

                if ($result === true) {
                    $this->session->set_flashdata('success', 'Project updated successfully');
                } elseif (is_array($result) && isset($result['message'])) {
                    $this->session->set_flashdata('error', $result['message']);
                } else {
                    $this->session->set_flashdata('error', 'Error updating project');
                }

                redirect('projects/view/' . $id);
            }
        }

        $data['project'] = $project;
        $data['title'] = 'Edit Project: ' . $project->name;
        $this->load->view('admin/header', $data);
        $this->load->view('admin/projects/edit', $data);
        $this->load->view('admin/footer');
    }

    public function view($id)
    {
        $project = $this->Project_model->get($id);

        if (!$project) {
            show_404();
        }

        // Check if user has permission to view this project
        $staff = $this->Staff_model->select_staff_byID($this->staff_id);
        $is_manager = ($project->manager_id == $this->staff_id);
        $is_same_department = false;

        // Only check department if user is a staff member (not super/hrm)
        if (!in_array($this->role, ['super', 'hrm', 'finance']) && $staff && !empty($staff)) {
            $staff = $staff[0]; // Get first result
            $is_same_department = ($project->department_id == $staff['department_id']);
        }

        if (!in_array($this->role, ['super', 'hrm', 'finance']) && !$is_manager && !$is_same_department) {
            $this->session->set_flashdata('error', 'You do not have permission to view this project');
            redirect('projects');
        }

        $data['project'] = $project;
        $data['title'] = 'View Project: ' . $project->name;
        $this->load->view('admin/header', $data);
        $this->load->view('admin/projects/view', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        // Only admin can delete projects
        if ($this->session->userdata('role') !== 'super') {
            $this->session->set_flashdata('error', 'You do not have permission to delete projects');
            redirect('projects');
        }

        // Validate project ID
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid project ID');
            redirect('projects');
        }

        // Check if project exists
        $project = $this->Project_model->get($id);
        if (!$project) {
            $this->session->set_flashdata('error', 'Project not found');
            redirect('projects');
        }

        $result = $this->Project_model->delete($id);

        if ($result) {
            $this->session->set_flashdata('success', 'Project "' . $project->name . '" deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Error deleting project. Please try again.');
        }

        redirect('projects');
    }

    public function approve($id)
    {
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'super') {
            $this->session->set_flashdata('error', 'You do not have permission to approve projects');
            redirect('projects');
        }

        // Validate project ID
        if (!$id || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid project ID');
            redirect('projects');
        }

        // Check if project exists
        $project = $this->Project_model->get($id);
        if (!$project) {
            $this->session->set_flashdata('error', 'Project not found');
            redirect('projects');
        }

        // Check if project is in pending status
        if ($project->status !== 'pending') {
            $this->session->set_flashdata('error', 'Only pending projects can be approved');
            redirect('projects/view/' . $id);
        }

        // Update project status to approved
        $result = $this->Project_model->update($id, ['status' => 'approved'], $project);

        if ($result === true) {
            $this->session->set_flashdata('success', 'Project "' . $project->name . '" approved successfully');
        } elseif (is_array($result) && isset($result['message'])) {
            $this->session->set_flashdata('error', $result['message']);
        } else {
            $this->session->set_flashdata('error', 'Error approving project. Please try again.');
        }

        redirect('projects/view/' . $id);
    }

    public function create()
    {
        // --- Initialize variables early to avoid undefined warnings ---
        $is_admin = in_array($this->role, ['super', 'hrm']);
        $is_hod = false;
        $staff = null;

        // --- Check role permissions ---
        if ($this->role === "staff") {
            $staff = $this->Staff_model->select_staff_byID($this->staff_id);
            if (empty($staff)) {
                $this->session->set_flashdata('error', 'Staff not found');
                redirect('projects');
            }

            // Select the first result
            $staff = $staff[0];

            // Check if staff is Head of Department
            $is_hod = $this->Department_model->is_head_of_department($this->staff_id);
        }

        // Only Super Admin, HRM, or HODs can create projects
        if (!$is_admin && !$is_hod) {
            $this->session->set_flashdata('error', 'You do not have permission to create projects');
            redirect('projects');
        }

        // --- Prepare View Data ---
        $data = [];
        $data['departments'] = $is_admin ? $this->Department_model->select_departments() : [];
        $data['staff_department'] = $staff['department_id'] ?? null;
        $data['is_admin'] = $is_admin;
        $data['is_hod'] = $is_hod;
        $data['title'] = 'Create New Project';

        // Get staff for manager selection based on role
        if ($is_admin) {
            // Super/HRM can select any staff as manager
            $data['staff_options'] = $this->Staff_model->select_staff();
        } else {
            // HOD can only select from their department
            $data['staff_options'] = $this->Staff_model->select_staff_byDept($data['staff_department']);
        }

        // --- Handle Form Submission ---
        if ($this->input->method() === 'post') {

            $this->form_validation->set_rules('name', 'Project Name', 'required');
            $this->form_validation->set_rules('client_email', 'Client Email', 'required|valid_email');
            $this->form_validation->set_rules('client_phone', 'Client Phone', 'required');
            $this->form_validation->set_rules('description_of_deliverables', 'Description of Deliverables', 'required');
            $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,medium,high]');
            $this->form_validation->set_rules('schedule_type', 'Schedule Type', 'required|in_list[monthly,annual,quarterly,one-off]');
            $this->form_validation->set_rules('manager_id', 'Project Manager', 'required');

            if ($this->form_validation->run() === TRUE) {

                // For admins, use selected department; for HODs, use their own
                $department_id = $is_admin
                    ? $this->input->post('department_id')
                    : $data['staff_department'];

                $manager_id = $this->input->post('manager_id', TRUE);

                // Validate manager selection based on role
                if ($is_admin) {
                    // Super/HRM can select any staff
                    $valid_manager = $this->Staff_model->select_staff_byID($manager_id);
                } else {
                    // HOD can only select from their department
                    $valid_manager = $this->Staff_model->select_staff_byID($manager_id);
                    if ($valid_manager && $valid_manager[0]['department_id'] != $data['staff_department']) {
                        $this->session->set_flashdata('error', 'You can only select managers from your department');
                        redirect('projects/create');
                    }
                }

                if (empty($valid_manager)) {
                    $this->session->set_flashdata('error', 'Invalid manager selected');
                    redirect('projects/create');
                }

                $project_data = [
                    'name' => $this->input->post('name', TRUE),
                    'client_email' => $this->input->post('client_email', TRUE),
                    'client_phone' => $this->input->post('client_phone', TRUE),
                    'description_of_deliverables' => $this->input->post('description_of_deliverables', TRUE),
                    'special_request' => $this->input->post('special_request', TRUE),
                    'priority' => $this->input->post('priority', TRUE),
                    'schedule_type' => $this->input->post('schedule_type', TRUE),
                    'schedule_date' => $this->input->post('schedule_date') ?: null,
                    'department_id' => $department_id,
                    'status' => 'pending',
                    'manager_id' => $manager_id,
                ];

                $project_id = $this->Project_model->create($project_data);

                if ($project_id) {
                    $this->session->set_flashdata('success', 'Project created successfully');
                    redirect('projects');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create project');
                }
            }
        }

        // --- Load Views ---
        $this->load->view('admin/header', $data);
        $this->load->view('admin/projects/create', $data);
        $this->load->view('admin/footer');
    }

    public function get_staff_by_department()
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $department_id = $this->input->post('department_id');

        if (!$department_id) {
            echo json_encode(['success' => false, 'message' => 'Department ID is required']);
            return;
        }

        // Check if user has permission to access staff data
        if (!in_array($this->role, ['super', 'hrm', 'finance'])) {
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            return;
        }

        $staff = $this->Staff_model->select_staff_byDept($department_id);

        if ($staff) {
            echo json_encode(['success' => true, 'staff' => $staff]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No staff found']);
        }
    }

    public function validate_receipt()
    {
        // Only allow AJAX requests
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Check if user has permission to validate receipts
        if (!in_array($this->role, ['super', 'finance'])) {
            echo json_encode(['success' => false, 'message' => 'Permission denied']);
            return;
        }

        $receipt_id = $this->input->post('receipt_id');

        if (!$receipt_id) {
            echo json_encode(['success' => false, 'message' => 'Receipt ID is required']);
            return;
        }

        // Load the Project model if not already loaded
        $this->load->model('Project_model');

        // Use the model's validate_receipt method
        $response = $this->Project_model->validate_receipt($receipt_id);

        if ($response) {
            echo json_encode([
                'success' => true,
                'message' => 'Receipt validated successfully',
                'data' => [
                    'receipt_id' => $response['data']['receipt_id'],
                    'validated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid receipt ID or receipt not found'
            ]);
        }
    }
}
