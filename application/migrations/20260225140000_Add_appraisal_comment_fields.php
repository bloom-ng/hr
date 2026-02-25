<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_appraisal_comment_fields extends CI_Migration
{
    public function up()
    {
        $fields = array(
            'strengths_comment' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'weaknesses_comment' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'training_needs_comment' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('appraisals_new', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('appraisals_new', 'strengths_comment');
        $this->dbforge->drop_column('appraisals_new', 'weaknesses_comment');
        $this->dbforge->drop_column('appraisals_new', 'training_needs_comment');
    }
}
