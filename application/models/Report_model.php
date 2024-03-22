<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
    public $table = "report_tbl";
    public $fields = [
        "id",
        "employee_name",
        "title",
        "department",
        "department_id",
        "status",
        "operation_unit",
        "supervisor",
        "team_lead",
        "date",
        "staff_id",
        "day_1_task",
        "day_1_total_hours",
        "day_2_task",
        "day_2_total_hours",
        "day_3_task",
        "day_3_total_hours",
        "day_4_task",
        "day_4_total_hours",
        "day_5_task",
        "day_5_total_hours"
    ];


    const REPORT_PENDING = 'pending';
    const REPORT_REVIEW = 'review';
    const REPORT_APPROVED = 'approved';
    const REPORT_DONE = 'done';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $qry = $this->db->get($this->table);
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
    }

    public function getWhere($where)
    {
        foreach ($where as $key => $value) {
            if (is_int($key)) {
                $this->db->where($value);
                continue;
            }
            $this->db->where($key, $value);
        }
        $qry = $this->db->get($this->table);
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
    }

    public function getAll()
    {
        $qry = $this->db->get($this->table);
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        $this->db->affected_rows();

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        $this->db->affected_rows();
    }
}
