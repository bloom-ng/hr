<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Events_model extends CI_Model
{
    public $table = "events";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_events()
    {
        $this->db->order_by('start_date', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function get_event($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function insert_event($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_event($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete_event($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function check_duplicate($title, $start_date, $end_date)
    {
        $this->db->where('title', $title);
        $this->db->where('start_date', $start_date);
        $this->db->where('end_date', $end_date);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    /**
     * Fetch events that start within the next 24 hours and haven't been reminded yet.
     */
    public function get_events_due_for_reminder()
    {
        $now   = date('Y-m-d H:i:s');
        $in24h = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $this->db->where('start_date >=', $now);
        $this->db->where('start_date <=', $in24h);
        $this->db->where('reminder_sent', 0);
        $this->db->order_by('start_date', 'ASC');

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Mark an event's reminder as sent so it won't be re-sent on the next cron run.
     */
    public function mark_reminder_sent($id)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, ['reminder_sent' => 1]);
    }
}
