<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Output $output
 * @property TransactionCategory_model $TransactionCategory_model
 * @property TransactionJournal_model $TransactionJournal_model
 * @property Log_model $Log_model
 */
class TransactionJournals extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }

        $role = $this->session->userdata('role');
        if (!in_array($role, ['finance', 'super'])) {
            show_error('You do not have permission to access Transaction Journals.', 403);
        }

        $this->load->model('TransactionCategory_model');
        $this->load->model('TransactionJournal_model');
        $this->load->model('Log_model');
    }

    public function index()
    {
        redirect(base_url() . 'transaction-journals/registery');
    }

    public function registery()
    {
        $categories = $this->TransactionCategory_model->get_all();
        $category_id = (int)($this->input->get('category_id') ?: ($categories[0]['id'] ?? 0));
        $from_date = $this->input->get('from_date') ?: date('Y-m-d', strtotime('-30 days'));
        $to_date = $this->input->get('to_date') ?: date('Y-m-d');

        $starting_balance = 0.0;
        $transactions = [];
        $rows = [];

        if ($category_id) {
            $starting_balance = (float)$this->TransactionJournal_model->get_balance_before($category_id, $from_date);
            $transactions = $this->TransactionJournal_model->get_transactions_in_range($category_id, $from_date, $to_date);

            $running = $starting_balance;
            foreach ($transactions as $t) {
                $amount = (float)$t['amount'];
                $payment = null;
                $deposit = null;
                if ($t['transaction_type'] === 'expense') {
                    $running -= $amount;
                    $payment = $amount;
                } else {
                    $running += $amount;
                    $deposit = $amount;
                }

                $t['payment_value'] = $payment;
                $t['deposit_value'] = $deposit;
                $t['balance_on'] = $running;
                $rows[] = $t;
            }
        }

        $data = [
            'categories' => $categories,
            'category_id' => $category_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'starting_balance' => $starting_balance,
            'rows' => $rows,
        ];

        $this->load->view('admin/header');
        $this->load->view('admin/transaction_journals/registery', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $categories = $this->TransactionCategory_model->get_all();
        $category_id = (int)($this->input->get('category_id') ?: ($categories[0]['id'] ?? 0));
        $payment_date = $this->input->get('payment_date') ?: date('Y-m-d');
        $transaction_type = $this->input->get('transaction_type') ?: 'expense';

        $balance_on = $category_id ? (float)$this->TransactionJournal_model->get_balance_asof($category_id, $payment_date) : 0.0;

        $data = [
            'categories' => $categories,
            'category_id' => $category_id,
            'payment_date' => $payment_date,
            'transaction_type' => $transaction_type,
            'balance_on' => $balance_on,
            'payment_methods' => \TransactionJournal_model::PAYMENT_METHODS,
        ];

        $this->load->view('admin/header');
        $this->load->view('admin/transaction_journals/add', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $transaction_type = $this->input->post('transaction_type');
        $category_id = (int)$this->input->post('category_id');
        $amount = $this->input->post('amount');
        $payed_to = $this->input->post('payed_to');
        $payment_date = $this->input->post('payment_date');
        $payment_method = $this->input->post('payment_method');

        $amount_num = is_numeric($amount) ? (float)$amount : 0.0;
        $valid_type = in_array($transaction_type, ['expense', 'deposit'], true);

        if (!$valid_type || !$category_id || $amount_num <= 0 || !$payment_date) {
            $this->session->set_flashdata('error', 'Please fill all required fields (valid type, category, amount, payment date).');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        if ($payment_method && !in_array($payment_method, \TransactionJournal_model::PAYMENT_METHODS, true)) {
            $this->session->set_flashdata('error', 'Invalid payment method selected.');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        $created_by = $this->session->userdata('staff_id') ?? $this->session->userdata('userid');

        $payload = [
            'category_id' => $category_id,
            'transaction_type' => $transaction_type,
            'amount' => $amount_num,
            'payed_to' => $payed_to,
            'payment_date' => $payment_date,
            'payment_method' => $payment_method,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $id = $this->TransactionJournal_model->insert($payload);

        $this->Log_model->log(
            'transaction-journals-insert',
            $created_by,
            "Inserted transaction journal row {$id}",
            $payload
        );

        $this->session->set_flashdata('success', 'Transaction saved successfully.');
        redirect(base_url() . 'transaction-journals/add?category_id=' . $category_id . '&payment_date=' . urlencode($payment_date) . '&transaction_type=' . urlencode($transaction_type));
    }

    public function get_balance()
    {
        $category_id = (int)$this->input->get('category_id');
        $payment_date = $this->input->get('payment_date') ?: date('Y-m-d');

        $balance_on = $category_id ? (float)$this->TransactionJournal_model->get_balance_asof($category_id, $payment_date) : 0.0;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'balance_on' => $balance_on,
            ]));
    }

    public function categories()
    {
        $data = [
            'categories' => $this->TransactionCategory_model->get_all(),
        ];

        $this->load->view('admin/header');
        $this->load->view('admin/transaction_journals/categories', $data);
        $this->load->view('admin/footer');
    }

    public function insert_category()
    {
        $name = $this->input->post('category_name');
        $created_by = $this->session->userdata('staff_id') ?? $this->session->userdata('userid');

        if (!trim((string)$name)) {
            $this->session->set_flashdata('error', 'Category name is required.');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        $id = $this->TransactionCategory_model->add($name, $created_by);

        $this->Log_model->log(
            'transaction-journals-category-insert',
            $created_by,
            "Inserted transaction category {$id}",
            ['category_name' => $name]
        );

        $this->session->set_flashdata('success', 'Category saved successfully.');
        redirect(base_url() . 'transaction-journals/categories');
    }
}

