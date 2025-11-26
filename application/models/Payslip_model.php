<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip_model extends CI_Model {

    public $table = "payroll";

    public function getPayrollPeriods() {
		$query = $this->db->query("
            SELECT DISTINCT period, payslip_status FROM `{$this->table}`
            GROUP BY period
            ORDER BY period DESC
        ");

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return [];
		}
	}

    public function updateStatus($period, $status) {
        $this->db->where('period', $period);
        $this->db->update($this->table, ['payslip_status' => $status]);
        return $this->db->affected_rows();
    }

    public function getPayslipsByPeriod($period) {
        $this->db->select('payroll.*, staff_tbl.staff_name, staff_tbl.email, staff_tbl.mobile, department_tbl.department_name');
        $this->db->from($this->table);
        $this->db->join('staff_tbl', 'staff_tbl.id = payroll.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->where('period', $period);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPayslipById($id) {
        $this->db->select('payroll.*, staff_tbl.staff_name, staff_tbl.email, staff_tbl.mobile, department_tbl.department_name');
        $this->db->from($this->table);
        $this->db->join('staff_tbl', 'staff_tbl.id = payroll.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->where('payroll.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getMyPayslips($staff_id) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('staff_id', $staff_id);
        $this->db->where('payslip_status', 1); // Only published payslips
        $this->db->order_by('period', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
