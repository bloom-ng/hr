<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {


    public function get_attendance_by_staff_and_date($staff_id, $date)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->where('date', $date);
        $query = $this->db->get('attendance_tbl');

        return $query->row_array(); // Returns a single row as an associative array
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

        return $this->db->insert_id(); // Returns the ID of the inserted row
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
