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
        if (!$project) return;

        $subject = "Project {$project->name} has been {$action}";
        $message = "Project: {$project->name}\n";
        $message .= "Status: " . ucfirst(str_replace('-', ' ', $project->status)) . "\n";
        $message .= "Priority: " . ucfirst($project->priority) . "\n";
        $message .= "Payment Status: " . ucfirst($project->payment_status) . "\n\n";
        $message .= "Click here to view: " . site_url("projects/view/{$project->id}");

        // Collect all recipient emails (deduplicated)
        $recipients = ["agharayetseyi@bloomdigitmedia.com", "hr@bloomdigitmedia.com", "finance@bloomdigitmedia.com", "davidaremu@bloomdigitmedia.com"];

        // 2. Get department HOD
        $departments = $this->Department_model->select_departments();
        if (!empty($departments)) {
            foreach ($departments as $department) {
                if ($department['id'] == $project->department_id && !empty($department['staff_id'])) {
                    $hod = $this->Staff_model->select_staff_byID($department['staff_id']);
                    if (!empty($hod) && !empty($hod[0]['email'])) {
                        $recipients[$hod[0]['email']] = $hod[0]['email'];
                    }
                    break;
                }
            }
        }

        // 3. Get project manager
        $manager = $this->Staff_model->select_staff_byID($project->manager_id);
        if (!empty($manager) && !empty($manager[0]['email'])) {
            $recipients[$manager[0]['email']] = $manager[0]['email'];
        }

        // Send email to all unique recipients
        foreach ($recipients as $email) {
            $this->email->clear();
            $this->email->to($email);
            $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
        }
    }

    /**
     * Notify the project manager when their project has been approved
     */
    public function notify_manager_approved($project_id)
    {
        $project = $this->get($project_id);
        if (!$project) {
            return false;
        }

        // Get project manager details
        $manager = $this->Staff_model->select_staff_byID($project->manager_id);
        if (!$manager || empty($manager[0]['email'])) {
            log_message('error', 'Could not notify manager for approved project: Manager not found or no email');
            return false;
        }

        $manager = $manager[0];
        $subject = "Project Approved: {$project->name}";
        
        $message = "Dear {$manager['staff_name']},\n\n";
        $message .= "Great news! Your project has been approved.\n\n";
        $message .= "Project Details:\n";
        $message .= "Name: {$project->name}\n";
        $message .= "Priority: " . ucfirst($project->priority) . "\n";
        $message .= "Status: Approved\n\n";
        $message .= "You can now proceed with the project execution.\n\n";
        $message .= "Click here to view the project: " . site_url("projects/view/{$project->id}") . "\n\n";
        $message .= "Best regards,\nBloom HR System";

        $this->email->clear();
        $this->email->to($manager['email']);
        $this->email->from('support@bloomdigitmedia.com', 'Bloom HR');
        $this->email->subject($subject);
        $this->email->message($message);
        
        if ($this->email->send()) {
            log_message('info', "Project approval notification sent to manager: {$manager['email']} for project ID: {$project_id}");
            return true;
        } else {
            log_message('error', 'Failed to send project approval notification: ' . $this->email->print_debugger(['headers']));
            return false;
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
