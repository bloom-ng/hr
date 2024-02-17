<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appraisal_model extends CI_Model {

    public function save_appraisal($data) {
        // Insert data into the 'appraisal_tbl' table
        $this->db->insert('appraisal_tbl', $data);
        
        // Check if the insertion was successful
        if ($this->db->affected_rows() > 0) {
            return true; // Return true if successful
        } else {
            return false; // Return false if failed
        }
    }
}
