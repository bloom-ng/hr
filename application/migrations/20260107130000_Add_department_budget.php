<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_department_budget extends CI_Migration {

    public function up()
    {
        // Table: department_budgets
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'department_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'year' => array(
                'type' => 'INT',
                'constraint' => 4,
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
            ),
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('department_budgets');

        // Table: budget_spending_logs
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'department_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00
            ),
            'date' => array(
                'type' => 'DATE',
            ),
            'status' => array(
                'type' => 'ENUM("pending","approved","rejected")',
                'default' => 'pending',
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('budget_spending_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('department_budgets');
        $this->dbforge->drop_table('budget_spending_logs');
    }
}
