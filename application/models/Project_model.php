<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project_model extends CI_Model
{

    protected $table = 'project';
    protected $primary_key = 'id';
    protected $allowed_fields = [
        'name',
        'client_email',
        'client_phone',
        'description_of_deliverables',
        'special_request',
        'priority',
        'schedule_type',
        'schedule_date',
        'department_id',
        'status',
        'payment_status',
        'receipt_id',
        'manager_id'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function get($id = null, $where = [])
    {
        if ($id) {
            return $this->db->where($this->primary_key, $id)->get($this->table)->row();
        }
        return $this->db->where($where)->get($this->table)->result();
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        $project_id = $this->db->insert_id();

        if ($project_id) {
            $this->notify_team($project_id, 'created');
        }

        return $project_id;
    }

    public function update($id, $data, $original_data = null)
    {
        $project = $this->get($id);

        // Only finance can update payment status and receipt ID
        $user_role = $this->session->userdata('role');
        if ($user_role !== 'finance') {
            unset($data['payment_status']);
            unset($data['receipt_id']);
        }

        // Only Super Admin can approve projects
        if ($user_role !== 'super' && isset($data['status']) && $data['status'] === 'approved') {
            return ['success' => false, 'message' => 'Only Super Admin can approve projects'];
        }

        // If receipt_id is being updated, validate it
        if (isset($data['receipt_id']) && $data['receipt_id'] !== $project->receipt_id) {
            if (!$this->validate_receipt($data['receipt_id'])) {
                return ['success' => false, 'message' => 'Invalid receipt number'];
            }
        }

        $this->db->where($this->primary_key, $id);
        $result = $this->db->update($this->table, $data);

        if ($result) {
            // Check if status, priority, or payment status was updated
            $notify_fields = ['status', 'priority', 'payment_status'];
            $should_notify = false;

            foreach ($notify_fields as $field) {
                if (isset($data[$field]) && $original_data && $original_data->$field != $data[$field]) {
                    $should_notify = true;
                    break;
                }
            }

            if ($should_notify) {
                $this->notify_team($id, 'updated');
            }
        }

        return $result;
    }

    public function delete($id)
    {
        // Check if project exists first
        $project = $this->get($id);
        if (!$project) {
            return false;
        }

        // Delete the project
        $this->db->where($this->primary_key, $id);
        $result = $this->db->delete($this->table);

        // Return true if deletion was successful
        return $result && $this->db->affected_rows() > 0;
    }

    public function get_by_department($department_id)
    {
        return $this->db->where('department_id', $department_id)->get($this->table)->result();
    }

    public function get_by_manager($manager_id)
    {
        return $this->db->where('manager_id', $manager_id)->get($this->table)->result();
    }

    protected function notify_team($project_id, $action)
    {
        $project = $this->get($project_id);

        $subject = "Project {$project->name} has been {$action}";
        $message = "Project: {$project->name}\n";
        $message .= "Status: " . ucfirst($project->status) . "\n";
        $message .= "Priority: " . ucfirst($project->priority) . "\n";
        $message .= "Payment Status: " . ucfirst($project->payment_status) . "\n\n";
        $message .= "Click here to view: " . site_url("projects/view/{$project->id}");

        if ($action === 'created') {
            // When project is created, notify all staff in the project's department
            $department_staff = $this->Staff_model->select_staff_byDept($project->department_id);

            if (!empty($department_staff)) {
                foreach ($department_staff as $staff_member) {
                    if (!empty($staff_member['email'])) {
                        $this->email->clear();
                        $this->email->to($staff_member['email']);
                        $this->email->from('noreply@bloom.com', 'Bloom HR');
                        $this->email->subject($subject);
                        $this->email->message($message);
                        $this->email->send();
                    }
                }
            }
        } else {
            // For updates, notify HOD and project manager only
            $departments = $this->Department_model->select_departments();

            // Send to HOD of the project's department
            if (isset($departments)) {
                foreach ($departments as $department) {
                    // Only notify HOD from the project's department
                    if ($department['id'] == $project->department_id) {
                        $staff = $this->Staff_model->select_staff_byID($department['staff_id']);
                        if (!empty($staff)) {
                            $this->email->clear();
                            $this->email->to($staff[0]['email']);
                            $this->email->from('noreply@bloom.com', 'Bloom HR');
                            $this->email->subject($subject);
                            $this->email->message($message);
                            $this->email->send();
                        }
                    }
                }
            }

            // Also notify the project manager
            $manager = $this->Staff_model->select_staff_byID($project->manager_id);
            if ($manager && !empty($manager[0]['email'])) {
                $this->email->clear();
                $this->email->to($manager[0]['email']);
                $this->email->from('noreply@bloom.com', 'Bloom HR');
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
            }
        }
    }

    protected function validate_receipt($receipt_id)
    {
        // In a real implementation, this would call an external API to validate the receipt
        // For now, we'll simulate a successful validation
        // Replace this with actual API call in production

        // Example API call (commented out):
        /*
        $this->load->library('curl');
        $response = $this->curl->simple_post('https://receipt-validation-api.example.com/validate', [
            'receipt_id' => $receipt_id,
            'api_key' => 'your_api_key_here'
        ]);
        
        $result = json_decode($response);
        return $result->valid === true;
        */

        // For development, return true if receipt is not empty
        return !empty($receipt_id);
    }
}
