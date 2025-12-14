<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_approved_amount_to_fund_request extends CI_Migration
{
    public function up()
    {
        $fields = [
            'approved_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => TRUE,
                'after' => 'amount'
            ]
        ];
        $this->dbforge->add_column('fund_request', $fields);
        
        // Populate existing approved requests (optional, but good practice if we want consistency)
        // If status is Approved, set approved_amount = amount
        $this->db->query("UPDATE fund_request SET approved_amount = amount WHERE status = 'Approved'");
    }

    public function down()
    {
        $this->dbforge->drop_column('fund_request', 'approved_amount');
    }
}
