<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_new_appraisal_system extends CI_Migration
{
    public function up()
    {
        // Table: appraisals_new
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'department_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'position' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'month_under_review' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            // Section 1: Performance Categories
            'rating_teamwork' => array('type' => 'INT', 'constraint' => 2),
            'comment_teamwork' => array('type' => 'TEXT'),
            
            'rating_communication' => array('type' => 'INT', 'constraint' => 2),
            'comment_communication' => array('type' => 'TEXT'),

            'rating_quality' => array('type' => 'INT', 'constraint' => 2),
            'comment_quality' => array('type' => 'TEXT'),

            'rating_timeliness' => array('type' => 'INT', 'constraint' => 2),
            'comment_timeliness' => array('type' => 'TEXT'),

            'rating_innovation' => array('type' => 'INT', 'constraint' => 2),
            'comment_innovation' => array('type' => 'TEXT'),

            'rating_professionalism' => array('type' => 'INT', 'constraint' => 2),
            'comment_professionalism' => array('type' => 'TEXT'),

            // Section 2: Task Tracking
            'tasks_assigned' => array('type' => 'INT', 'constraint' => 5),
            'tasks_completed' => array('type' => 'INT', 'constraint' => 5),
            'completion_rate' => array('type' => 'DECIMAL', 'constraint' => '5,2'),
            'accuracy_rate' => array(
                'type' => 'ENUM',
                'constraint' => ['Excellent', 'Good', 'Fair', 'Needs Improvement'],
                'default' => 'Good'
            ),

            // Section 3: Performance Summary (JSON arrays)
            'strengths' => array('type' => 'TEXT', 'null' => TRUE), 
            'weaknesses' => array('type' => 'TEXT', 'null' => TRUE),

            // Section 4: Training & Development (JSON array)
            'training_needs' => array('type' => 'TEXT', 'null' => TRUE),

            // Section 5: Goals (JSON array)
            'next_month_goals' => array('type' => 'TEXT', 'null' => TRUE),

            // Section 6: Remarks
            'hod_remarks' => array('type' => 'TEXT', 'null' => TRUE),
            'employee_remarks' => array('type' => 'TEXT', 'null' => TRUE),
            'hr_remarks' => array('type' => 'TEXT', 'null' => TRUE),
            'hr_approval_date' => array('type' => 'DATE', 'null' => TRUE),

            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['pending', 'review', 'approved'],
                'default' => 'pending'
            ),
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('appraisals_new');

        // Table: appraisal_kpas
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'appraisal_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'category' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'expected_output' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'actual_output' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'rating' => array(
                'type' => 'INT',
                'constraint' => 2,
                'null' => TRUE
            ),
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('appraisal_kpas');
    }

    public function down()
    {
        $this->dbforge->drop_table('appraisal_kpas');
        $this->dbforge->drop_table('appraisals_new');
    }
}
