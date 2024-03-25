<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

    public $table = "log";
    public $fields = [
        "id",
    ];

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function log($action, $user, $details, $payload)
    {
        $payload = $this->arrayToString($payload);

        $data = [
            'action' => $action,
            'datetime' => date("Y-m-d H:i:s"),
            'user_id' => $this->session->userdata('userid'),
            'details' => $details ." \n" .$payload
        ];
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function arrayToString($payload) {
        $stringRepresentation = '';
        foreach ($payload as $key => $value) {
            $stringRepresentation .= $key . ': ' . $value . ', ';
        }
        // Remove the trailing comma and space
        $stringRepresentation = rtrim($stringRepresentation, ', ');
        return $stringRepresentation;
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



}
