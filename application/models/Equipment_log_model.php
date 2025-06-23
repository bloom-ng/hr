<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipment_log_model extends CI_Model
{
    // Status constants
    const STATUS_IN_USE = 'in_use';
    const STATUS_RETURNED = 'returned';

    // Request status constants
    const REQUEST_PENDING = 'pending';
    const REQUEST_APPROVED = 'approved';
    const REQUEST_DECLINED = 'declined';
    const REQUEST_CANCELLED = 'cancelled';

    public function insert($data)
    {
        $this->db->insert('equipment_log_tbl', $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('equipment_log_tbl');
        return ($query->num_rows() > 0) ? $query->row_array() : null;
    }

    public function get_staff_logs($staff_id)
    {
        $this->db->select('equipment_log_tbl.*, equipment_tbl.image as equipment_image, equipment_tbl.name as equipment_name, equipment_tbl.unique_id as equipment_serial, staff_tbl.staff_name');
        $this->db->from('equipment_log_tbl');
        $this->db->join('equipment_tbl', 'equipment_log_tbl.equipment_id = equipment_tbl.id', 'left');
        $this->db->join('staff_tbl', 'equipment_log_tbl.staff_id = staff_tbl.id', 'left');
        $this->db->where('equipment_log_tbl.staff_id', $staff_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_equipment_logs($equipment_id)
    {
        $this->db->select('equipment_log_tbl.*, staff_tbl.staff_name, equipment_tbl.name as equipment_name, equipment_tbl.unique_id as equipment_serial');
        $this->db->from('equipment_log_tbl');
        $this->db->join('equipment_tbl', 'equipment_log_tbl.equipment_id = equipment_tbl.id', 'left');
        $this->db->join('staff_tbl', 'equipment_log_tbl.staff_id = staff_tbl.id', 'left');
        $this->db->where('equipment_log_tbl.equipment_id', $equipment_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('equipment_log_tbl', $data);
        return $this->db->affected_rows() > 0;
    }

    public function get_pending_requests()
    {
        $this->db->select('equipment_log_tbl.*, equipment_tbl.image as equipment_image, equipment_tbl.name as equipment_name, equipment_tbl.unique_id as equipment_serial, staff_tbl.staff_name, staff_tbl.pic as user_image, staff_tbl.department_id as user_department');
        $this->db->from('equipment_log_tbl');
        $this->db->join('equipment_tbl', 'equipment_log_tbl.equipment_id = equipment_tbl.id', 'left');
        $this->db->join('staff_tbl', 'equipment_log_tbl.staff_id = staff_tbl.id', 'left');
        $this->db->where('equipment_log_tbl.request_status', self::REQUEST_PENDING);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_active_loans()
    {
        $this->db->select('equipment_log_tbl.*, equipment_tbl.name as equipment_name, staff_tbl.staff_name');
        $this->db->from('equipment_log_tbl');
        $this->db->join('equipment_tbl', 'equipment_log_tbl.equipment_id = equipment_tbl.id', 'left');
        $this->db->join('staff_tbl', 'equipment_log_tbl.staff_id = staff_tbl.id', 'left');
        $this->db->where('equipment_log_tbl.status', self::STATUS_IN_USE);
        $this->db->where('equipment_log_tbl.request_status', self::REQUEST_APPROVED);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_logs()
    {
        $this->db->select('equipment_log_tbl.*, equipment_tbl.name as equipment_name, equipment_tbl.unique_id as equipment_serial, staff_tbl.staff_name');
        $this->db->from('equipment_log_tbl');
        $this->db->join('equipment_tbl', 'equipment_log_tbl.equipment_id = equipment_tbl.id', 'left');
        $this->db->join('staff_tbl', 'equipment_log_tbl.staff_id = staff_tbl.id', 'left');
        $this->db->order_by('equipment_log_tbl.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
