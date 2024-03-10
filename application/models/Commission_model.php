<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission_model extends CI_Model {

    public $table = "commissions";
    public $fields = [
        "id",
        "staff_id",
        "client",
        "total",
        "commission",
        "date",
        "comments",
        "status"
    ];

    
    const COMMISSION_PENDING = 1;
    const COMMISSION_PAID = 2;

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
        $this->db->order_by("id", "DESC");
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

	public function userCommission($staff_id) {
		$query = $this->db->query("
            SELECT ROUND(SUM(total * commission / 100), 2) AS user_commission
            FROM commissions
            WHERE staff_id = $staff_id AND status = '1'
        ");

		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row->user_commission;
		} else {
			return 0; // Return 0 if no unpaid commissions found for the user
		}
	}

    public function getStatusMapping()
    {
        return [
            static::COMMISSION_PENDING => "Pending",
            static::COMMISSION_PAID => "Paid",
            null => ""
        ];
    }

}
