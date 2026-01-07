<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_model extends CI_Model {

    // Budget methods
    public function save_budget($department_id, $year, $amount)
    {
        // Check if exists
        $this->db->where('department_id', $department_id);
        $this->db->where('year', $year);
        $query = $this->db->get('department_budgets');

        if ($query->num_rows() > 0) {
            $this->db->where('department_id', $department_id);
            $this->db->where('year', $year);
            return $this->db->update('department_budgets', ['amount' => $amount]);
        } else {
            return $this->db->insert('department_budgets', [
                'department_id' => $department_id,
                'year' => $year,
                'amount' => $amount
            ]);
        }
    }

    public function get_budget($department_id, $year)
    {
        $this->db->where('department_id', $department_id);
        $this->db->where('year', $year);
        $query = $this->db->get('department_budgets');
        return $query->row_array();
    }

    public function get_all_budgets($year)
    {
        $this->db->select('department_budgets.*, department_tbl.department_name');
        $this->db->from('department_budgets');
        $this->db->join('department_tbl', 'department_tbl.id = department_budgets.department_id', 'left');
        $this->db->where('department_budgets.year', $year);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Spending Logs methods
    public function add_spending_log($data)
    {
        $this->db->insert('budget_spending_logs', $data);
        return $this->db->insert_id();
    }

    public function get_spending_logs($department_id, $year = null)
    {
        $this->db->select('budget_spending_logs.*, staff_tbl.staff_name as creator_name');
        $this->db->from('budget_spending_logs');
        $this->db->join('staff_tbl', 'staff_tbl.id = budget_spending_logs.created_by', 'left');
        $this->db->where('budget_spending_logs.department_id', $department_id);
        if ($year) {
             $this->db->where('YEAR(budget_spending_logs.date)', $year);
        }
        $this->db->order_by('budget_spending_logs.date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_log($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('budget_spending_logs');
        return $query->row_array();
    }

    public function update_log_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('budget_spending_logs', ['status' => $status]);
    }

    public function get_total_spent($department_id, $year)
    {
        $this->db->select_sum('amount');
        $this->db->where('department_id', $department_id);
        $this->db->where('status', 'approved');
        $this->db->where('YEAR(date)', $year);
        $query = $this->db->get('budget_spending_logs');
        $result = $query->row_array();
        return $result['amount'] ? $result['amount'] : 0;
    }
    
    public function get_total_pending($department_id, $year)
    {
        $this->db->select_sum('amount');
        $this->db->where('department_id', $department_id);
        $this->db->where('status', 'pending');
        $this->db->where('YEAR(date)', $year);
        $query = $this->db->get('budget_spending_logs');
        $result = $query->row_array();
        return $result['amount'] ? $result['amount'] : 0;
    }
}
