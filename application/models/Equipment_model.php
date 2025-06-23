<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipment_model extends CI_Model
{
    // Status constants
    const STATUS_DAMAGED = 'damaged';
    const STATUS_IN_REPAIR = 'in_repair';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MISSING = 'missing';
    const STATUS_AVAILABLE = 'available';

    public function insert($data)
    {
        $this->db->insert('equipment_tbl', $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('equipment_tbl');
        return ($query->num_rows() > 0) ? $query->row_array() : null;
    }

    public function get_by_unique_id($unique_id)
    {
        $this->db->where('unique_id', $unique_id);
        $query = $this->db->get('equipment_tbl');
        return ($query->num_rows() > 0) ? $query->row_array() : null;
    }

    public function get_all()
    {
        $query = $this->db->get('equipment_tbl');
        return $query->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('equipment_tbl', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('equipment_tbl');
        return $this->db->affected_rows() > 0;
    }

    public function get_available_equipment()
    {
        $this->db->where('status', self::STATUS_AVAILABLE);
        $query = $this->db->get('equipment_tbl');
        return $query->result_array();
    }
}
