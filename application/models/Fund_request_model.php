<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fund_request_model extends CI_Model
{
    protected $table = 'fund_request';

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_status($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    public function update_payment_status($id, $payment_status)
    {
        return $this->update($id, ['payment_status' => $payment_status]);
    }

    public function list_all()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function list_by_department($department_id)
    {
        $this->db->where('department_id', $department_id);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function list_by_staff($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}
