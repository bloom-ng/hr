<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionJournal_model extends CI_Model
{
    public $table = 'transaction_journal_tbl';

    public const PAYMENT_METHODS = [
        'Cash',
        'Transfer',
        'Cheque',
        'POS',
        'Card',
        'Other',
    ];

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return (int)$this->db->insert_id();
    }

    public function get_balance_asof($category_id, $date)
    {
        return $this->get_net_balance($category_id, null, $date, true);
    }

    public function get_balance_before($category_id, $from_date)
    {
        // payment_date < from_date
        return $this->get_net_balance($category_id, null, $from_date, false);
    }

    public function get_transactions_in_range($category_id, $from_date, $to_date)
    {
        $this->db->select('tj.*, tc.category_name');
        $this->db->from($this->table . ' tj');
        $this->db->join('transaction_categories_tbl tc', 'tc.id = tj.category_id', 'left');
        $this->db->where('tj.category_id', (int)$category_id);
        if ($from_date) {
            $this->db->where('tj.payment_date >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('tj.payment_date <=', $to_date);
        }
        $this->db->order_by('tj.payment_date', 'ASC');
        $this->db->order_by('tj.id', 'ASC');
        return $this->db->get()->result_array();
    }

    private function get_net_balance($category_id, $from_date, $to_date, $inclusive_to_date = true)
    {
        $category_id = (int)$category_id;

        // Deposits
        $this->db->select_sum('amount');
        $this->db->from($this->table);
        $this->db->where('category_id', $category_id);
        $this->db->where('transaction_type', 'deposit');
        if ($from_date) {
            $this->db->where('payment_date >=', $from_date);
        }
        if ($to_date) {
            $op = $inclusive_to_date ? '<=' : '<';
            // Keep escaping enabled so DATE values are quoted correctly.
            $this->db->where("payment_date {$op}", $to_date);
        }
        $deposit_row = $this->db->get()->row_array();
        $deposit_sum = isset($deposit_row['amount']) && $deposit_row['amount'] !== null ? (float)$deposit_row['amount'] : 0.0;

        // Expenses
        $this->db->select_sum('amount');
        $this->db->from($this->table);
        $this->db->where('category_id', $category_id);
        $this->db->where('transaction_type', 'expense');
        if ($from_date) {
            $this->db->where('payment_date >=', $from_date);
        }
        if ($to_date) {
            $op = $inclusive_to_date ? '<=' : '<';
            // Keep escaping enabled so DATE values are quoted correctly.
            $this->db->where("payment_date {$op}", $to_date);
        }
        $expense_row = $this->db->get()->row_array();
        $expense_sum = isset($expense_row['amount']) && $expense_row['amount'] !== null ? (float)$expense_row['amount'] : 0.0;

        return $deposit_sum - $expense_sum;
    }
}

