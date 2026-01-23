<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
        $this->load->model('Budget_model');
        $this->load->model('Department_model');
    }

    public function index()
    {
        // Only Super Admin and Finance can see the overview list
        // HODs should probably be redirected to their own department manage page?
        // But if an HOD accesses /budget, what happens?
        // Request says: "HOD can see their departmental budget."
        // Maybe index shows the list if Super/Finance. If HOD, redirect to manage() for their dept?
        
        $role = $this->session->userdata('role');
        $staff_id = $this->session->userdata('staff_id');
        
        if ($role == 'staff') { // Assuming HOD is staff but check if is_head_of_department
             $department_id = $this->session->userdata('department_id'); // Assuming this is in session or fetch from staff info
             // Check if HOD
             if ($this->Department_model->is_head_of_department($staff_id)) {
                 redirect(base_url().'budget/manage/'.$department_id);
             } else {
                 show_error('You do not have permission to view budgets.', 403);
             }
        } elseif (!in_array($role, ['super', 'finance'])) {
             show_error('You do not have permission to view budgets.', 403);
        }

        $year = date('Y');
        
        // Fetch all departments
        $departments = $this->Department_model->select_departments();
        
        // Prepare data
        $budget_data = [];
        if ($departments) {
            foreach ($departments as $dept) {
                $budget = $this->Budget_model->get_budget($dept['id'], $year);
                $spent = $this->Budget_model->get_total_spent($dept['id'], $year);
                $pending = $this->Budget_model->get_total_pending($dept['id'], $year);
                
                $amount = $budget ? $budget['amount'] : 0;
                $balance = $amount - $spent;
                
                $budget_data[] = [
                    'department_id' => $dept['id'],
                    'department_name' => $dept['department_name'],
                    'budget_amount' => $amount,
                    'spent_amount' => $spent,
                    'pending_amount' => $pending,
                    'balance_amount' => $balance
                ];
            }
        }

        $data['budgets'] = $budget_data;
        $data['year'] = $year;
        
        $this->load->view('admin/header');
        $this->load->view('admin/budget/index', $data);
        $this->load->view('admin/footer');
    }

    public function manage($department_id)
    {
        $role = $this->session->userdata('role');
        $staff_id = $this->session->userdata('staff_id');
        
        // Authorization check
        if (!in_array($role, ['super', 'finance'])) {
             // If HOD, strict check
            if ($this->Department_model->is_head_of_department($staff_id)) {
                 $my_dept = $this->session->userdata('department_id');
                 if ($my_dept != $department_id) {
                     show_error('Access Denied', 403);
                 }
            } else {
                 show_error('Access Denied', 403);
            }
        }

        $year = date('Y');
        
        $budget = $this->Budget_model->get_budget($department_id, $year);
        $spent = $this->Budget_model->get_total_spent($department_id, $year);
        $pending = $this->Budget_model->get_total_pending($department_id, $year);
        
        $amount = $budget ? $budget['amount'] : 0;
        
        $data['budget_info'] = [
            'department_id' => $department_id,
            'department_name' => $this->Department_model->get_department_name($department_id),
            'budget_amount' => $amount,
            'spent_amount' => $spent,
            'pending_amount' => $pending,
            'balance_amount' => $amount - $spent
        ];
        
        $data['logs'] = $this->Budget_model->get_spending_logs($department_id, $year);
        $data['role'] = $role;
        
        $this->load->view('admin/header');
        $this->load->view('admin/budget/manage', $data);
        $this->load->view('admin/footer');
    }

    public function save_budget()
    {
        if (!in_array($this->session->userdata('role'), ['super', 'finance'])) {
            // Only finance can add spending logs? Request says "then finance can add spending logs"
             $this->session->set_flashdata('error', "Only Finance can add expenses.");
             redirect($_SERVER['HTTP_REFERER']);
        }
        
        $department_id = $this->input->post('department_id');
        $amount = $this->input->post('amount');
        $year = date('Y');
        
        $this->Budget_model->save_budget($department_id, $year, $amount);
        
        $this->session->set_flashdata('success', "Budget Updated Successfully");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function add_expense()
    {
        if (!in_array($this->session->userdata('role'), ['super', 'finance'])) {
            // Only finance can add spending logs? Request says "then finance can add spending logs"
             $this->session->set_flashdata('error', "Only Finance can add expenses.");
             redirect($_SERVER['HTTP_REFERER']);
        }
        
        $data = [
            'department_id' => $this->input->post('department_id'),
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'amount' => $this->input->post('amount'),
            'date' => $this->input->post('date'),
            'status' => 'pending',
            'created_by' => $this->session->userdata('staff_id') ?? $this->session->userdata('userid'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->Budget_model->add_spending_log($data);
        $this->session->set_flashdata('success', "Expense Log Added Successfully (Pending Approval)");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update_status($id, $status)
    {
        if (!in_array($this->session->userdata('role'), ['super', 'finance'])) {
            $this->session->set_flashdata('error', "Access Denied");
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        if (in_array($status, ['approved', 'rejected'])) {
            $this->Budget_model->update_log_status($id, $status);
            $this->session->set_flashdata('success', "Expense Updated Successfully");
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
}
