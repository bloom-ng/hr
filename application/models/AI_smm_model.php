<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_smm_model extends CI_Model {

    public $table = "ai_smm";
    public $fields = [
        "id",
        "brand_info",
        "target_audience",
        "timeframe",
        "platform",
        "generated_content",
        "created_at"
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
        if($qry->num_rows() > 0)
        {
            return $qry->result_array();
        }
    }

    public function getAll()
    {
        $qry = $this->db->get($this->table);
        if($qry->num_rows() > 0)
        {
            return $qry->result_array();
        }
    }

    // Add more methods as needed
}