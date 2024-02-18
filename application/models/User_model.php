<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    const USERTYPE_SUPERADMIN = 1;
    const USERTYPE_ADMIN = 2;
    const USERTYPE_FINANCE = 3;
    const USERTYPE_EMPLOYEE = 4;
    
    public $table = "users";

    public $roles = ["staff", "hrm", "finance", "super"];
    public $admins = ["hrm", "finance", "super"];


    function logindata($un)
    {
        $this->db->where('username',$un);
        $qry=$this->db->get("users");
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function insert_user($data)
    {
        $this->db->insert("users",$data);
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
        $qry=$this->db->get('country_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
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
        if($qry->num_rows()>0)
        {
            return $qry->result_array();
        }
    }

    public static function getAdminRoles()
    {
        return ["hrm", "finance", "super"];
    }




}
