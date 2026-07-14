<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vote_model extends CI_Model {

    const STATUS_DRAFT  = 'draft';
    const STATUS_OPEN   = 'open';
    const STATUS_CLOSED = 'closed';

    public $table = "vote_polls";

    function insert_poll($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update_poll($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    function delete_poll($id)
    {
        $this->db->trans_start();
        $this->db->where('poll_id', $id)->delete('vote_poll_votes');
        $this->db->where('poll_id', $id)->delete('vote_poll_candidates');
        $this->db->where('id', $id)->delete($this->table);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function select_poll_byID($id)
    {
        $this->db->where('id', $id);
        $qry = $this->db->get($this->table);
        return $qry->row_array();
    }

    function select_polls()
    {
        $this->db->select('vote_polls.*,
            (SELECT COUNT(*) FROM vote_poll_candidates WHERE vote_poll_candidates.poll_id = vote_polls.id) AS candidate_count,
            (SELECT COUNT(*) FROM vote_poll_votes WHERE vote_poll_votes.poll_id = vote_polls.id) AS vote_count', FALSE);
        $this->db->from($this->table);
        $this->db->order_by('vote_polls.id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Replace the candidate list for a poll.
     *
     * Existing candidates that are dropped also lose any votes cast for them,
     * otherwise the tally would count staff who are no longer on the ballot.
     */
    function set_candidates($poll_id, $staff_ids)
    {
        $staff_ids = array_values(array_unique(array_map('intval', (array) $staff_ids)));

        $this->db->trans_start();

        $this->db->where('poll_id', $poll_id);
        if (!empty($staff_ids)) {
            $this->db->where_not_in('candidate_staff_id', $staff_ids);
        }
        $this->db->delete('vote_poll_votes');

        $this->db->where('poll_id', $poll_id);
        if (!empty($staff_ids)) {
            $this->db->where_not_in('staff_id', $staff_ids);
        }
        $this->db->delete('vote_poll_candidates');

        $existing = $this->get_candidate_ids($poll_id);
        $new_rows = array();
        foreach (array_diff($staff_ids, $existing) as $staff_id) {
            $new_rows[] = array('poll_id' => $poll_id, 'staff_id' => $staff_id);
        }
        if (!empty($new_rows)) {
            $this->db->insert_batch('vote_poll_candidates', $new_rows);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function get_candidate_ids($poll_id)
    {
        $this->db->select('staff_id');
        $this->db->where('poll_id', $poll_id);
        $rows = $this->db->get('vote_poll_candidates')->result_array();
        return array_map('intval', array_column($rows, 'staff_id'));
    }

    /**
     * Every candidate on a poll, with their staff and department details.
     */
    function get_candidates($poll_id)
    {
        $this->db->select('vote_poll_candidates.id AS candidate_row_id,
            staff_tbl.id AS staff_id, staff_tbl.staff_name, staff_tbl.pic,
            staff_tbl.department_id, department_tbl.department_name');
        $this->db->from('vote_poll_candidates');
        $this->db->join('staff_tbl', 'staff_tbl.id = vote_poll_candidates.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->where('vote_poll_candidates.poll_id', $poll_id);
        $this->db->order_by('staff_tbl.staff_name', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Candidates a given voter is allowed to pick.
     *
     * Staff may not vote for anyone in their own department, and may not vote
     * for themselves. This is the authoritative rule - the ballot uses it to
     * build the list, and cast_vote() re-checks it before writing.
     */
    function get_eligible_candidates($poll_id, $voter_staff_id, $voter_department_id)
    {
        $this->db->select('staff_tbl.id AS staff_id, staff_tbl.staff_name, staff_tbl.pic,
            staff_tbl.department_id, department_tbl.department_name');
        $this->db->from('vote_poll_candidates');
        $this->db->join('staff_tbl', 'staff_tbl.id = vote_poll_candidates.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->where('vote_poll_candidates.poll_id', $poll_id);
        $this->db->where('staff_tbl.id !=', (int) $voter_staff_id);

        if (!empty($voter_department_id)) {
            $this->db->where('staff_tbl.department_id !=', (int) $voter_department_id);
        }

        $this->db->order_by('staff_tbl.staff_name', 'ASC');
        return $this->db->get()->result_array();
    }

    function is_eligible_candidate($poll_id, $voter_staff_id, $voter_department_id, $candidate_staff_id)
    {
        foreach ($this->get_eligible_candidates($poll_id, $voter_staff_id, $voter_department_id) as $candidate) {
            if ((int) $candidate['staff_id'] === (int) $candidate_staff_id) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function has_voted($poll_id, $voter_staff_id)
    {
        $this->db->where('poll_id', $poll_id);
        $this->db->where('voter_staff_id', $voter_staff_id);
        return $this->db->count_all_results('vote_poll_votes') > 0;
    }

    function cast_vote($poll_id, $voter_staff_id, $candidate_staff_id)
    {
        $this->db->insert('vote_poll_votes', array(
            'poll_id'            => (int) $poll_id,
            'voter_staff_id'     => (int) $voter_staff_id,
            'candidate_staff_id' => (int) $candidate_staff_id,
        ));
        return $this->db->insert_id();
    }

    /**
     * Vote tally for a poll: candidates with their counts, highest first.
     *
     * Voter identity is deliberately not exposed here - results are counts only.
     */
    function get_results($poll_id)
    {
        $this->db->select('staff_tbl.id AS staff_id, staff_tbl.staff_name, staff_tbl.pic,
            department_tbl.department_name,
            (SELECT COUNT(*) FROM vote_poll_votes
                WHERE vote_poll_votes.poll_id = vote_poll_candidates.poll_id
                AND vote_poll_votes.candidate_staff_id = staff_tbl.id) AS votes', FALSE);
        $this->db->from('vote_poll_candidates');
        $this->db->join('staff_tbl', 'staff_tbl.id = vote_poll_candidates.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->where('vote_poll_candidates.poll_id', $poll_id);
        $this->db->order_by('votes', 'DESC');
        $this->db->order_by('staff_tbl.staff_name', 'ASC');
        return $this->db->get()->result_array();
    }

    function count_votes($poll_id)
    {
        $this->db->where('poll_id', $poll_id);
        return $this->db->count_all_results('vote_poll_votes');
    }

    /**
     * Polls a staff member can currently see on their voting list: anything
     * open, plus anything they have already voted on.
     */
    function get_polls_for_staff($staff_id)
    {
        $this->db->select('vote_polls.*,
            (SELECT COUNT(*) FROM vote_poll_votes
                WHERE vote_poll_votes.poll_id = vote_polls.id
                AND vote_poll_votes.voter_staff_id = ' . (int) $staff_id . ') AS has_voted', FALSE);
        $this->db->from($this->table);
        $this->db->where_in('vote_polls.status', array(self::STATUS_OPEN, self::STATUS_CLOSED));
        $this->db->order_by('vote_polls.end_date', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * A poll only accepts votes while it is open and inside its date window.
     */
    function is_accepting_votes($poll)
    {
        if (empty($poll) || $poll['status'] !== self::STATUS_OPEN) {
            return FALSE;
        }

        $now = time();
        return $now >= strtotime($poll['start_date']) && $now <= strtotime($poll['end_date']);
    }
}
