<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_model extends CI_Model {

	public $table = "bonus_tbl";
	public $fields = array(
		"id",
		"staff_id",
		"date",
		"amount",
		"reason",
		"status",
	);

	const BONUS_PAID = 'paid';
	const BONUS_PENDING = 'pending';

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get($id)
	{
		$this->db->where('id',$id);
		$qry = $this->db->get($this->table);
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
		$qry = $this->db->get($this->table);
		if($qry->num_rows()>0)
		{
			return $qry->result_array();
		}
	}

	public function getAll()
	{
		$qry = $this->db->get($this->table);
		if($qry->num_rows()>0)
		{
			return $qry->result_array();
		}
	}



	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
		$this->db->affected_rows();
	}


	public function update($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		$this->db->affected_rows();
	}

	public function userBonus($staff_id) {
		$this->db->select_sum('amount');
		$this->db->where('staff_id', $staff_id);
		$this->db->where('status', 'pending');
		$query = $this->db->get('bonus_tbl');

		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->amount;
		} else {
			return 0; // Return 0 if no bonus found for the user
		}
	}

	public function getStatusMapping()
	{
		return [
			static::BONUS_PENDING => "Pending",
			static::BONUS_PAID => "Paid",
			null => ""
		];
	}
}
