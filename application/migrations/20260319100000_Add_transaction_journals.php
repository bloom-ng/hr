<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_transaction_journals extends CI_Migration {

    public function up()
    {
        // Table: transaction_categories_tbl
        if (!$this->db->table_exists('transaction_categories_tbl')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'category_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE,
                ),
                'created_at datetime default current_timestamp',
                'updated_at datetime default current_timestamp on update current_timestamp',
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('category_name', FALSE, TRUE); // unique
            $this->dbforge->create_table('transaction_categories_tbl');
        }

        // Table: transaction_journal_tbl
        if (!$this->db->table_exists('transaction_journal_tbl')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'category_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                ),
                'transaction_type' => array(
                    'type' => 'ENUM("expense","deposit")',
                    'default' => 'expense',
                ),
                'amount' => array(
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0.00
                ),
                'payed_to' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE,
                ),
                'payment_date' => array(
                    'type' => 'DATE',
                ),
                'payment_method' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => TRUE,
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE,
                ),
                'created_at datetime default current_timestamp',
                'updated_at datetime default current_timestamp on update current_timestamp',
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('category_id');
            $this->dbforge->add_key('payment_date');
            $this->dbforge->create_table('transaction_journal_tbl');
        }

        // Seed default category if missing
        $this->db->where('category_name', 'Petty cash');
        $exists = $this->db->get('transaction_categories_tbl')->row_array();
        if (!$exists) {
            $createdBy = $this->session && $this->session->userdata('staff_id')
                ? $this->session->userdata('staff_id')
                : ($this->session && $this->session->userdata('userid') ? $this->session->userdata('userid') : null);

            $this->db->insert('transaction_categories_tbl', array(
                'category_name' => 'Petty cash',
                'created_by' => $createdBy,
            ));
        }
    }

    public function down()
    {
        if ($this->db->table_exists('transaction_journal_tbl')) {
            $this->dbforge->drop_table('transaction_journal_tbl');
        }
        if ($this->db->table_exists('transaction_categories_tbl')) {
            $this->dbforge->drop_table('transaction_categories_tbl');
        }
    }
}

