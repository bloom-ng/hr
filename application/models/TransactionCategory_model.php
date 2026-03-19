<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionCategory_model extends CI_Model
{
    public $table = 'transaction_categories_tbl';

    public function get_all()
    {
        $this->db->order_by('category_name', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function get($id)
    {
        $this->db->where('id', (int)$id);
        return $this->db->get($this->table)->row_array();
    }

    public function add($category_name, $created_by = null)
    {
        $category_name = trim((string)$category_name);
        if ($category_name === '') {
            return null;
        }

        // Avoid duplicates (case-insensitive)
        $this->db->where('LOWER(category_name) =', strtolower($category_name), false);
        $exists = $this->db->get($this->table)->row_array();
        if ($exists) {
            return (int)$exists['id'];
        }

        $this->db->insert($this->table, [
            'category_name' => $category_name,
            'created_by' => $created_by,
        ]);
        return (int)$this->db->insert_id();
    }
}

