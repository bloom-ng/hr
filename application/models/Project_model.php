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
        if (!in_array($user_role, ['super', 'finance'])) {
            unset($data['payment_status']);
            unset($data['receipt_id']);
        }

        // If receipt_id is being updated, validate it
        if (isset($data['receipt_id']) && $data['receipt_id'] !== $project->receipt_id) {
            if (!$this->validate_receipt($data['receipt_id'])) {
                return ['success' => false, 'message' => 'Invalid receipt number'];
            }
        }

        // Only Super Admin can approve projects
        if ($user_role !== 'super' && isset($data['status']) && $data['status'] === 'approved') {
            return ['success' => false, 'message' => 'Only Super Admin can approve projects'];
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
                        $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
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
                            $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
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

    public function validate_receipt($receipt_id)
    {
        if (empty($receipt_id)) {
            return false;
        }

        // Make API call to validate receipt
        $api_url = "https://ads.bloomdigitmedia.com/api/receipt/confirm/{$receipt_id}";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($curl_error) {
            log_message('error', 'Receipt validation cURL error: ' . $curl_error);
            return false;
        }

        // Check HTTP status code
        if ($http_code !== 200) {
            log_message('error', 'Receipt validation API returned HTTP code: ' . $http_code);
            return false;
        }

        // Parse JSON response
        $result = json_decode($response, true);


        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Receipt validation API returned invalid JSON');
            return false;
        }

        // Check if the response indicates success
        if (isset($result['status']) && $result['status'] === 'success' && isset($result['data'])) {
            // Log successful validation for audit purposes
            log_message('info', 'Receipt validated successfully: ' . $receipt_id);

            return $result;
        }

        // Log failure reason
        $message = isset($result['message']) ? $result['message'] : 'Unknown error';
        log_message('error', 'Receipt validation failed: ' . $message);
        return false;
    }
}
