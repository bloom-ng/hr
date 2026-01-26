<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Events_model extends CI_Model
{
    public $table = "events";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_events()
    {
        $this->db->order_by('start_date', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function get_event($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function insert_event($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_event($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete_event($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
