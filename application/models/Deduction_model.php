<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deduction_model extends CI_Model {

    public $table = "deductions";
    public $fields = [
        "id",
        "staff_id",
        "amount",
        "date",
        "reason",
        "status"
    ];

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
            }
            $this->db->where($key, $value);
        }
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


}
