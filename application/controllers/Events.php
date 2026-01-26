<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Events extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
        $this->load->model('Events_model');
    }

    // View for Staff (Calendar)
    public function index()
    {
        $this->load->view('admin/header');
        $this->load->view('events/calendar');
        $this->load->view('admin/footer');
    }

    // JSON Endpoint for FullCalendar
    public function get_events()
    {
        $events = $this->Events_model->get_all_events();
        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'],
                'description' => $event['description'],
                // Add color or other properties if needed based on type
                // 'color' => '#3a87ad' 
            ];
        }
        echo json_encode($data);
    }

    // View for HRM/Admin (List of Events)
    public function manage()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $data['events'] = $this->Events_model->get_all_events();
        $this->load->view('admin/header');
        $this->load->view('events/manage', $data);
        $this->load->view('admin/footer');
    }

    // Create Event Form
    public function create()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $this->load->view('admin/header');
        $this->load->view('events/form');
        $this->load->view('admin/footer');
    }

    // Store Event
    public function store()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'created_by' => $this->session->userdata('userid')
            ];
            $this->Events_model->insert_event($data);
            $this->session->set_flashdata('success', 'Event created successfully.');
            redirect('events/manage');
        }
    }

    // Edit Event Form
    public function edit($id)
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $data['event'] = $this->Events_model->get_event($id);
        if (empty($data['event'])) {
            show_404();
        }

        $this->load->view('admin/header');
        $this->load->view('events/form', $data);
        $this->load->view('admin/footer');
    }

    // Update Event
    public function update($id)
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date')
            ];
            $this->Events_model->update_event($id, $data);
            $this->session->set_flashdata('success', 'Event updated successfully.');
            redirect('events/manage');
        }
    }

    // Delete Event
    public function delete($id)
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $this->Events_model->delete_event($id);
        $this->session->set_flashdata('success', 'Event deleted successfully.');
        redirect('events/manage');
    }
}
