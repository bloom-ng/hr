<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_draft_status extends CI_Migration
{
    public function up()
    {
        // Add 'draft' to the status enum
        // Current values: 'pending', 'hr_approved', 'staff_replied', 'final'
        // New values: 'draft', 'pending', 'hr_approved', 'staff_replied', 'final'
        
        $sql = "ALTER TABLE appraisals_new MODIFY COLUMN status ENUM('draft', 'pending', 'hr_approved', 'staff_replied', 'final') DEFAULT 'draft'";
        $this->db->query($sql);
    }

    public function down()
    {
        // Revert back (note: this might fail if there are 'draft' records, but standard down migration logic)
        $sql = "ALTER TABLE appraisals_new MODIFY COLUMN status ENUM('pending', 'hr_approved', 'staff_replied', 'final') DEFAULT 'pending'";
        $this->db->query($sql);
    }
}
