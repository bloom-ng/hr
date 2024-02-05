<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {


    public function get_attendance_by_staff_and_date($staff_id, $date)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('date', $date);
        $query = $this->db->get('attendance_tbl');

        return ($query->num_rows() > 0) ? $query->row_array() : null;
    }


    public function check_attendance($staff_id, $from_date, $to_date) {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('date >=', $from_date);
        $this->db->where('date <=', $to_date);
        $query = $this->db->get('attendance_tbl');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function export_attendance_by_date_range($from_date, $to_date)
    {
        $this->db->where('date >=', $from_date);
        $this->db->where('date <=', $to_date);
        $query = $this->db->get('attendance_tbl');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function get_attendance_by_date_range($from_date, $to_date)
    {
        $this->db->select('attendance_tbl.*, staff_tbl.staff_name'); // Include staff_name
        $this->db->from('attendance_tbl');
        $this->db->join('staff_tbl', 'attendance_tbl.staff_id = staff_tbl.id', 'left'); // Assuming staff_id is the foreign key

        // Add your date range condition
        $this->db->where('attendance_tbl.date >=', $from_date);
        $this->db->where('attendance_tbl.date <=', $to_date);
        // $query = $this->db->get('attendance_tbl');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function update_attendance($attendance_id, $data)
    {
        $this->db->where('id', $attendance_id);
        $this->db->update('attendance_tbl', $data);

        return $this->db->affected_rows() > 0;
    }

    public function insert_attendance($data)
    {
        $this->db->insert('attendance_tbl', $data);

        return $this->db->insert_id();
    }

    function select_attendance()
    {
        $qry=$this->db->get('attendance_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_attendance_byID($id)
    {

        $this->db->where('id',$id);
        $qry=$this->db->get('attendance_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function delete_attendance($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("attendance_tbl");
        $this->db->affected_rows();
    }

}
