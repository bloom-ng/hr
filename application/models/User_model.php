<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    const USERTYPE_SUPERADMIN = 1;
    const USERTYPE_ADMIN = 2;
    const USERTYPE_FINANCE = 3;
    const USERTYPE_EMPLOYEE = 4;

    public $table = "users";

    public $roles = ["staff", "hrm", "finance", "super"];
    public $admins = ["hrm", "finance", "super"];


    public function logindata($username)
    {
        $this->db->select('users.*, staff_tbl.staff_name');
        $this->db->from('users');
        $this->db->join('staff_tbl', 'staff_tbl.user_id = users.id', 'left');
        $this->db->where('users.username', $username);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }


    public function get($id)
    {
        $this->db->where('id', $id);
        $qry = $this->db->get($this->table);
        if ($qry->num_rows() > 0) {
            return $qry->result_array();
        }
    }

    function insert_user($data)
    {
        $this->db->insert("users", $data);
        return $this->db->insert_id();
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        $this->db->affected_rows();
    }


    function select_countries()
    {
        $qry = $this->db->get('country_tbl');
        if ($qry->num_rows() > 0) {
            $result = $qry->result_array();
            return $result;
        }
    }

    function delete_login_byID($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("users");
        $this->db->affected_rows();
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

    public static function getAdminRoles()
    {
        return ["hrm", "finance", "super"];
    }
}
