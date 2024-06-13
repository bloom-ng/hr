<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anonymous_model extends CI_Model
{

    public $table = "anonymous";
    public $fields = [
        "id",
        "subject",
        "message"
    ];

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

    public function updateWhere($data, $where)
    {
        foreach ($where as $key => $value) {
            if (is_int($key)) {
                $this->db->where($value);
                continue;
            }
            $this->db->where($key, $value);
        }
        $this->db->update($this->table, $data);
        $this->db->affected_rows();
    }

    public function deleteWhere($where)
    {
        foreach ($where as $key => $value) {
            if (is_int($key)) {
                $this->db->where($value);
                continue;
            }
            $this->db->where($key, $value);
        }
        $this->db->delete($this->table);
        $this->db->affected_rows();
    }

    public function getRaw($sql)
    {
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }
}
