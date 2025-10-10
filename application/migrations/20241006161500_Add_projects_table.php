<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_projects_table extends CI_Migration
{

    public function up()
    {
        // Create projects table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'client_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'client_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description_of_deliverables' => [
                'type' => 'LONGTEXT',
            ],
            'special_request' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high'],
                'default' => 'low',
            ],
            'schedule_type' => [
                'type' => 'ENUM',
                'constraint' => ['monthly', 'annual', 'quarterly', 'one-off'],
                'default' => 'monthly',
            ],
            'schedule_date' => [
                'type' => 'DATE',
                'null' => TRUE,
            ],
            'department_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'in-progress', 'done', 'delivered'],
                'default' => 'pending',
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'refunded'],
                'default' => 'pending',
            ],
            'receipt_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
            ],
            'manager_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT fk_project_department FOREIGN KEY (department_id) REFERENCES department_tbl(id) ON DELETE CASCADE');
        $this->dbforge->add_field('CONSTRAINT fk_project_manager FOREIGN KEY (manager_id) REFERENCES staff_tbl(id) ON DELETE CASCADE');

        $this->dbforge->create_table('projects', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('projects', TRUE);
    }
}
