<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_payslip_status extends CI_Migration {

    public function up()
    {
        $fields = array(
            'payslip_status' => array(
                'type' => 'INT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'remark' 
            ),
        );
        $this->dbforge->add_column('payroll', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('payroll', 'payslip_status');
    }
}
