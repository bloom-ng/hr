<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_appraisal_status_enum extends CI_Migration
{
    public function up()
    {
        // Modify the status column
        // Note: CI_Migration doesn't have a direct 'modify_column' for enums easily in all drivers, 
        // but we can use straight SQL for this specific change to ensure it works on MySQL.
        // The previous default was 'pending'. New values: 'pending', 'hr_approved', 'staff_replied', 'final'
        
        $sql = "ALTER TABLE appraisals_new MODIFY COLUMN status ENUM('pending', 'hr_approved', 'staff_replied', 'final') DEFAULT 'pending'";
        $this->db->query($sql);
    }

    public function down()
    {
        // Revert back to original
        $sql = "ALTER TABLE appraisals_new MODIFY COLUMN status ENUM('pending', 'review', 'approved') DEFAULT 'pending'";
        $this->db->query($sql);
    }
}
