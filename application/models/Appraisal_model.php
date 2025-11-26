<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appraisal_model extends CI_Model {
	const APPRAISAL_PENDING = 'pending';
	const APPRAISAL_REVIEW = 'review';
	const APPRAISAL_APPROVED = 'approved';
	const APPRAISAL_DONE = 'done';

    public function save_appraisal($data) {
        // Insert data into the 'appraisal_tbl' table
        $this->db->insert('appraisal_tbl', $data);

        // Check if the insertion was successful
        if ($this->db->affected_rows() > 0) {
            return true; // Return true if successful
        } else {
            return false; // Return false if failed
        }
    }

	public function get_appraisal($data) {
		// Insert data into the 'appraisal_tbl' table
		$qry=$this->db->get('attendance_tbl');
		if($qry->num_rows()>0)
		{
			$result=$qry->result_array();
			return $result;
		}
	}

	public function list_appraisals($user_id)
	{
		$this->db->where('staff_id', $user_id);
		$this->db->order_by('id', 'DESC');
		$qry = $this->db->get('appraisal_tbl');
		if($qry->num_rows()>0)
		{
			return $qry->result_array();
		}
	}

	public function get($id)
	{
		$this->db->where('id', $id);
		$qry = $this->db->get('appraisal_tbl');
		if($qry->num_rows()>0)
		{
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
//		$this->db->where('id', $id);
		$this->db->order_by('id', 'DESC');
		$qry = $this->db->get('appraisal_tbl');
		if($qry->num_rows()>0)
		{
			return $qry->result_array();
		}
	}

	public function update($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('appraisal_tbl', $data);
		$this->db->affected_rows();

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

}
