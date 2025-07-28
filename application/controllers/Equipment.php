<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
    }

    // Admin/HR Operations
    public function index()
    {
        $this->verify_admin_hr_access();
        $data['equipment'] = $this->Equipment_model->get_all();
        $this->load->view('admin/header');
        $this->load->view('admin/equipment/index', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $this->verify_admin_hr_access();

        if ($this->input->method() === 'post') {
            $this->load->library('image_lib');
            $config['upload_path'] = 'uploads/equipment/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            // Handle image upload
            if (!$this->upload->do_upload('equipment_image')) {
                $image = 'default-equipment.jpg';
            } else {
                $image_data = $this->upload->data();

                $configer = array(
                    'image_library'   => 'gd2',
                    'source_image'    => $image_data['full_path'],
                    'maintain_ratio'  => TRUE,
                    'width'          => 150,
                    'height'         => 150,
                    'quality'        => 50
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();

                $image = $image_data['file_name'];
            }

            $data = array(
                'name' => $this->input->post('name'),
                'unique_id' => $this->input->post('unique_id'),
                'status' => Equipment_model::STATUS_AVAILABLE,
                'image' => $image,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $equipment_id = $this->Equipment_model->insert($data);
            if ($equipment_id) {
                $this->session->set_flashdata('success', 'Equipment added successfully');
                redirect('equipment');
            } else {
                $this->session->set_flashdata('error', 'Failed to add equipment');
                redirect('equipment/add');
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/equipment/add');
        $this->load->view('admin/footer');
    }

    public function edit($id)
    {
        $this->verify_admin_hr_access();

        $data['equipment'] = $this->Equipment_model->get($id);
        if (!$data['equipment']) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $update_data = array(
                'name' => $this->input->post('name'),
                'unique_id' => $this->input->post('unique_id'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->load->library('image_lib');
            $config['upload_path'] = 'uploads/equipment/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('equipment_image')) {
                // No new image uploaded, proceed with other updates
                if ($this->Equipment_model->update($id, $update_data)) {
                    $this->session->set_flashdata('success', 'Equipment updated successfully');
                    redirect('equipment');
                }
            } else {
                $image_data = $this->upload->data();

                $configer = array(
                    'image_library'   => 'gd2',
                    'source_image'    => $image_data['full_path'],
                    'maintain_ratio'  => TRUE,
                    'width'          => 150,
                    'height'         => 150,
                    'quality'        => 50
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();

                $update_data['image'] = $image_data['file_name'];

                if ($this->Equipment_model->update($id, $update_data)) {
                    $this->session->set_flashdata('success', 'Equipment updated successfully');
                    redirect('equipment');
                }
            }
            $this->session->set_flashdata('error', 'Failed to update equipment');
            redirect('equipment/edit/' . $id);
        }

        print_r($data);

        $this->load->view('admin/header');
        $this->load->view('admin/equipment/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->verify_admin_hr_access();

        if ($this->Equipment_model->delete($id)) {
            $this->session->set_flashdata('success', 'Equipment deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete equipment');
        }
        redirect('equipment');
    }

    public function changeStatus()
    {
        $this->verify_admin_hr_access();

        $equipment_id = $this->input->post('equipment_id');
        $new_status = $this->input->post('status');

        $allowed_statuses = [
            Equipment_model::STATUS_DAMAGED,
            Equipment_model::STATUS_IN_REPAIR,
            Equipment_model::STATUS_MISSING,
            Equipment_model::STATUS_AVAILABLE
        ];

        if (!in_array($new_status, $allowed_statuses)) {
            $this->output->set_status_header(400);
            echo json_encode(['error' => 'Invalid status']);
            return;
        }

        if ($this->Equipment_model->update($equipment_id, ['status' => $new_status])) {
            echo json_encode(['success' => true]);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['error' => 'Failed to update status']);
        }
    }

    public function markAsReturned($log_id)
    {
        $this->verify_admin_hr_access();

        $log = $this->Equipment_log_model->get($log_id);
        if (!$log || $log['status'] !== Equipment_log_model::STATUS_IN_USE) {
            show_404();
        }

        // Update log status
        $this->Equipment_log_model->update($log_id, [
            'status' => Equipment_log_model::STATUS_RETURNED,
            'returned_date' => date('Y-m-d H:i:s')
        ]);

        // Update equipment status
        $this->Equipment_model->update($log['equipment_id'], [
            'status' => Equipment_model::STATUS_AVAILABLE,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Equipment marked as returned successfully');
        redirect('equipment/logs');
    }

    public function pendingRequests()
    {
        $this->verify_admin_hr_access();
        $data['requests'] = $this->Equipment_log_model->get_pending_requests();

        $this->load->view('admin/header');
        $this->load->view('admin/equipment/pending-requests', $data);
        $this->load->view('admin/footer');
    }

    public function approveRequest($log_id)
    {
        $this->verify_admin_hr_access();

        $log = $this->Equipment_log_model->get($log_id);
        if (!$log || $log['request_status'] !== Equipment_log_model::REQUEST_PENDING) {
            show_404();
        }

        // Update log status
        $this->Equipment_log_model->update($log_id, [
            'request_status' => Equipment_log_model::REQUEST_APPROVED,
            'status' => Equipment_log_model::STATUS_IN_USE,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Update equipment status
        $this->Equipment_model->update($log['equipment_id'], [
            'status' => Equipment_model::STATUS_IN_USE
        ]);

        $this->session->set_flashdata('success', 'Equipment request approved successfully');
        redirect('equipment/pendingRequests');
    }

    public function declineRequest($log_id)
    {
        $this->verify_admin_hr_access();

        $log = $this->Equipment_log_model->get($log_id);
        if (!$log || $log['request_status'] !== Equipment_log_model::REQUEST_PENDING) {
            show_404();
        }

        $this->Equipment_log_model->update($log_id, [
            'request_status' => Equipment_log_model::REQUEST_DECLINED,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Equipment request declined successfully');
        redirect('equipment/pendingRequests');
    }

    public function logs($equipment_id = null)
    {
        $this->verify_admin_hr_access();

        // Get logs data
        if ($equipment_id) {
            $data['logs'] = $this->Equipment_log_model->get_equipment_logs($equipment_id);
            // Get equipment details
            $data['equipment'] = $this->Equipment_model->get($equipment_id);
            if (!$data['equipment']) {
                show_404();
            }
        } else {
            $data['logs'] = $this->Equipment_log_model->get_all_logs();
        }

        $data['total_logs'] = count($data['logs']);

        $this->load->view('admin/header');
        $this->load->view('admin/equipment/logs', $data);
        $this->load->view('admin/footer');
    }

    // Staff Operations
    public function requestEquipment()
    {
        $this->verify_staff_access();

        if ($this->input->method() === 'post') {
            $equipment_id = $this->input->post('equipment_id');
            $purpose = $this->input->post('purpose');

            $equipment = $this->Equipment_model->get($equipment_id);
            if (!$equipment || $equipment['status'] !== Equipment_model::STATUS_AVAILABLE) {
                $this->session->set_flashdata('error', 'Equipment is not available for request');
                redirect('equipment/requestEquipment');
            }

            $log_data = array(
                'equipment_id' => $equipment_id,
                'staff_id' => $this->session->userdata('staff_id'),
                'purpose' => $purpose,
                'requested_date' => date('Y-m-d H:i:s'),
                'status' => Equipment_log_model::STATUS_IN_USE,
                'request_status' => Equipment_log_model::REQUEST_PENDING,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if ($this->Equipment_log_model->insert($log_data)) {
                $this->session->set_flashdata('success', 'Equipment request submitted successfully');

                $departments = $this->Department_model->select_departments();

                if (isset($departments)) {
                    foreach ($departments as $department) {
                        $staff = $this->Staff_model->select_staff_byID($department['staff_id']);

                        $this->send_email_notification($staff[0]['email'], $equipment['name']);
                    }
                }

                redirect('equipment/myRequests');
            }
        }

        $data['available_equipment'] = $this->Equipment_model->get_available_equipment();

        $this->load->view('admin/header');
        $this->load->view('staff/equipment/request', $data);
        $this->load->view('admin/footer');
    }

    public function myRequests()
    {
        $this->verify_staff_access();
        $staff_id = $this->session->userdata('staff_id');
        $data['requests'] = $this->Equipment_log_model->get_staff_logs($staff_id);

        $this->load->view('admin/header');
        $this->load->view('staff/equipment/my-requests', $data);
        $this->load->view('admin/footer');
    }

    public function cancelRequest($log_id)
    {
        $this->verify_staff_access();

        $log = $this->Equipment_log_model->get($log_id);
        if (!$log || $log['request_status'] !== Equipment_log_model::REQUEST_PENDING) {
            show_404();
        }

        $this->Equipment_log_model->update($log_id, [
            'request_status' => Equipment_log_model::REQUEST_CANCELLED,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Equipment request cancelled successfully');
        redirect('equipment/myRequests');
    }

    // Helper methods
    private function verify_admin_hr_access()
    {
        if (!$this->session->userdata('role') == 'super' || !$this->session->userdata('role') == 'hrm') {
            show_error('Unauthorized access', 403);
        }
    }

    private function verify_staff_access()
    {
        if (!$this->session->userdata('role') == 'staff') {
            show_error('Unauthorized access', 403);
        }
    }

    private function send_email_notification($employee_email, $equipment_name)
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
        $this->email->subject("Equipment Request");
        $this->email->message("Dear HOD a staff just requested an equipment " . $equipment_name);

        // Send email
        $this->email->send();
    }
}
