<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vote extends CI_Controller
{
    /** Roles allowed to create and manage polls and to see results. */
    private $manager_roles = ['hrm', 'super'];

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
        $this->load->model('Vote_model');
        $this->load->model('Staff_model');
    }

    private function _is_manager()
    {
        return in_array($this->session->userdata('role'), $this->manager_roles);
    }

    private function _require_manager()
    {
        if (!$this->_is_manager()) {
            redirect(base_url());
        }
    }

    /**
     * The staff record for the logged-in user.
     *
     * Only the 'staff' role gets staff_id/department_id put in the session at
     * login, so for every other role we look the record up by user_id. Returns
     * NULL for accounts with no staff record at all (they cannot vote).
     */
    private function _current_staff()
    {
        $staff_id = $this->session->userdata('staff_id');
        if (!empty($staff_id)) {
            $staff = $this->Staff_model->getWhere(['id' => $staff_id]);
            return empty($staff) ? NULL : $staff[0];
        }

        $staff = $this->Staff_model->getWhere(['user_id' => $this->session->userdata('userid')]);
        return empty($staff) ? NULL : $staff[0];
    }

    // ---------------------------------------------------------------------
    // Staff-facing voting
    // ---------------------------------------------------------------------

    /**
     * List of polls the logged-in staff member can vote on.
     */
    public function index()
    {
        $staff = $this->_current_staff();
        if (empty($staff)) {
            $this->session->set_flashdata('error', 'Your account is not linked to a staff record, so you cannot vote.');
            redirect(base_url());
        }

        $data['polls'] = $this->Vote_model->get_polls_for_staff($staff['id']);
        $data['is_manager'] = $this->_is_manager();

        $this->load->view('admin/header');
        $this->load->view('vote/index', $data);
        $this->load->view('admin/footer');
    }

    /**
     * The ballot for a single poll.
     */
    public function cast($poll_id)
    {
        $staff = $this->_current_staff();
        if (empty($staff)) {
            $this->session->set_flashdata('error', 'Your account is not linked to a staff record, so you cannot vote.');
            redirect(base_url());
        }

        $poll = $this->Vote_model->select_poll_byID($poll_id);
        if (empty($poll) || $poll['status'] === Vote_model::STATUS_DRAFT) {
            show_404();
        }

        $data['poll'] = $poll;
        $data['staff'] = $staff;
        $data['has_voted'] = $this->Vote_model->has_voted($poll_id, $staff['id']);
        $data['accepting_votes'] = $this->Vote_model->is_accepting_votes($poll);
        $data['candidates'] = $this->Vote_model->get_eligible_candidates(
            $poll_id,
            $staff['id'],
            $staff['department_id']
        );

        $this->load->view('admin/header');
        $this->load->view('vote/cast', $data);
        $this->load->view('admin/footer');
    }

    /**
     * Record a vote.
     *
     * Every rule is re-checked here rather than trusted from the ballot, since
     * the posted candidate_id can be anything.
     */
    public function submit($poll_id)
    {
        $staff = $this->_current_staff();
        if (empty($staff)) {
            $this->session->set_flashdata('error', 'Your account is not linked to a staff record, so you cannot vote.');
            redirect(base_url());
        }

        $poll = $this->Vote_model->select_poll_byID($poll_id);
        if (empty($poll)) {
            show_404();
        }

        if (!$this->Vote_model->is_accepting_votes($poll)) {
            $this->session->set_flashdata('error', 'This poll is not open for voting.');
            redirect('vote/cast/' . $poll_id);
        }

        if ($this->Vote_model->has_voted($poll_id, $staff['id'])) {
            $this->session->set_flashdata('error', 'You have already voted on this poll.');
            redirect('vote/cast/' . $poll_id);
        }

        $candidate_id = (int) $this->input->post('candidate_id');
        if (empty($candidate_id)) {
            $this->session->set_flashdata('error', 'Please select a candidate before submitting.');
            redirect('vote/cast/' . $poll_id);
        }

        // Enforces both the department rule and the no-self-vote rule.
        if (!$this->Vote_model->is_eligible_candidate($poll_id, $staff['id'], $staff['department_id'], $candidate_id)) {
            $this->session->set_flashdata('error', 'You cannot vote for that person. Staff may not vote for themselves or for anyone in their own department.');
            redirect('vote/cast/' . $poll_id);
        }

        $this->Vote_model->cast_vote($poll_id, $staff['id'], $candidate_id);
        $this->session->set_flashdata('success', 'Your vote has been recorded.');
        redirect('vote/cast/' . $poll_id);
    }

    // ---------------------------------------------------------------------
    // HRM / super poll management
    // ---------------------------------------------------------------------

    public function manage()
    {
        $this->_require_manager();

        $data['polls'] = $this->Vote_model->select_polls();

        $this->load->view('admin/header');
        $this->load->view('vote/manage', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {
        $this->_require_manager();

        $data['staff'] = $this->Staff_model->select_staff();
        $data['selected_candidates'] = [];

        $this->load->view('admin/header');
        $this->load->view('vote/form', $data);
        $this->load->view('admin/footer');
    }

    public function store()
    {
        $this->_require_manager();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('candidates[]', 'Candidates', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $error = $this->_validate_dates();
        if ($error !== NULL) {
            $this->session->set_flashdata('error', $error);
            redirect('vote/create');
        }

        $poll_id = $this->Vote_model->insert_poll([
            'title'       => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'start_date'  => $this->input->post('start_date'),
            'end_date'    => $this->input->post('end_date'),
            'status'      => $this->_posted_status(),
            'created_by'  => $this->session->userdata('userid'),
        ]);

        $this->Vote_model->set_candidates($poll_id, $this->input->post('candidates'));

        $this->session->set_flashdata('success', 'Poll created successfully.');
        redirect('vote/manage');
    }

    public function edit($poll_id)
    {
        $this->_require_manager();

        $data['poll'] = $this->Vote_model->select_poll_byID($poll_id);
        if (empty($data['poll'])) {
            show_404();
        }

        $data['staff'] = $this->Staff_model->select_staff();
        $data['selected_candidates'] = $this->Vote_model->get_candidate_ids($poll_id);

        $this->load->view('admin/header');
        $this->load->view('vote/form', $data);
        $this->load->view('admin/footer');
    }

    public function update($poll_id)
    {
        $this->_require_manager();

        $poll = $this->Vote_model->select_poll_byID($poll_id);
        if (empty($poll)) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('candidates[]', 'Candidates', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($poll_id);
            return;
        }

        $error = $this->_validate_dates();
        if ($error !== NULL) {
            $this->session->set_flashdata('error', $error);
            redirect('vote/edit/' . $poll_id);
        }

        $this->Vote_model->update_poll($poll_id, [
            'title'       => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'start_date'  => $this->input->post('start_date'),
            'end_date'    => $this->input->post('end_date'),
            'status'      => $this->_posted_status(),
        ]);

        $this->Vote_model->set_candidates($poll_id, $this->input->post('candidates'));

        $this->session->set_flashdata('success', 'Poll updated successfully. Any votes cast for removed candidates were discarded.');
        redirect('vote/manage');
    }

    public function close($poll_id)
    {
        $this->_require_manager();

        if (empty($this->Vote_model->select_poll_byID($poll_id))) {
            show_404();
        }

        $this->Vote_model->update_poll($poll_id, ['status' => Vote_model::STATUS_CLOSED]);
        $this->session->set_flashdata('success', 'Poll closed. No further votes will be accepted.');
        redirect('vote/manage');
    }

    public function delete($poll_id)
    {
        $this->_require_manager();

        if (empty($this->Vote_model->select_poll_byID($poll_id))) {
            show_404();
        }

        $this->Vote_model->delete_poll($poll_id);
        $this->session->set_flashdata('success', 'Poll and all of its votes deleted.');
        redirect('vote/manage');
    }

    /**
     * Vote tally. Restricted to HRM/super, and shows counts only - never who
     * voted for whom.
     */
    public function results($poll_id)
    {
        $this->_require_manager();

        $data['poll'] = $this->Vote_model->select_poll_byID($poll_id);
        if (empty($data['poll'])) {
            show_404();
        }

        $data['results'] = $this->Vote_model->get_results($poll_id);
        $data['total_votes'] = $this->Vote_model->count_votes($poll_id);

        $this->load->view('admin/header');
        $this->load->view('vote/results', $data);
        $this->load->view('admin/footer');
    }

    private function _posted_status()
    {
        $status = $this->input->post('status');
        $allowed = [Vote_model::STATUS_DRAFT, Vote_model::STATUS_OPEN, Vote_model::STATUS_CLOSED];
        return in_array($status, $allowed) ? $status : Vote_model::STATUS_DRAFT;
    }

    private function _validate_dates()
    {
        $start = strtotime($this->input->post('start_date'));
        $end   = strtotime($this->input->post('end_date'));

        if ($start === FALSE || $end === FALSE) {
            return 'Please provide a valid start and end date.';
        }
        if ($end <= $start) {
            return 'The end date must be after the start date.';
        }
        return NULL;
    }
}
