<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hod_Report_model extends CI_Model
{
    public $table = "hod_report_tbl";
    public $fields = [
        "id",
        "staff_id",
        "department_id",
        "department_name",
        "activities",
        "achievement",
        "growth_analysis",
        "challenges",
        "target_for_next_month",
        "recommendations",
        "conclusion",
        "date",
        "status",
    ];


    const HOD_REPORT_PENDING = 'pending';
    const HOD_REPORT_REVIEW = 'review';
    const HOD_REPORT_APPROVED = 'approved';
    const HOD_REPORT_DONE = 'done';

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
        $this->db->order_by('id', 'DESC');
        $qry = $this->db->get($this->table);
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
    }

    public function getAll()
    {
        $this->db->order_by('id', 'DESC');
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
