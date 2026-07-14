<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_vote_polls extends CI_Migration {

    public function up()
    {
        // Table: vote_polls
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'start_date' => array(
                'type' => 'DATETIME',
            ),
            'end_date' => array(
                'type' => 'DATETIME',
            ),
            'status' => array(
                'type' => 'ENUM("draft","open","closed")',
                'default' => 'draft',
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
        $this->dbforge->create_table('vote_polls');

        // Table: vote_poll_candidates - the staff put forward as options on a poll.
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'poll_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('vote_poll_candidates');

        // A staff member may only appear once per poll.
        $this->db->query('ALTER TABLE `vote_poll_candidates`
            ADD UNIQUE KEY `uniq_poll_candidate` (`poll_id`, `staff_id`)');

        // Table: vote_poll_votes
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'poll_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'voter_staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'candidate_staff_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('vote_poll_votes');

        // One vote per staff member per poll, enforced by the database rather than
        // by the application alone.
        $this->db->query('ALTER TABLE `vote_poll_votes`
            ADD UNIQUE KEY `uniq_poll_voter` (`poll_id`, `voter_staff_id`)');

        $this->db->query('ALTER TABLE `vote_poll_votes`
            ADD KEY `idx_poll_candidate` (`poll_id`, `candidate_staff_id`)');
    }

    public function down()
    {
        $this->dbforge->drop_table('vote_poll_votes');
        $this->dbforge->drop_table('vote_poll_candidates');
        $this->dbforge->drop_table('vote_polls');
    }
}
