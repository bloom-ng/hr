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

    // Import Event View
    public function import()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $this->load->view('admin/header');
        $this->load->view('events/import');
        $this->load->view('admin/footer');
    }

    // Process CSV Upload
    public function upload_csv()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        if (isset($_FILES['csv_file']['name']) && $_FILES['csv_file']['name'] != '') {
            $path = $_FILES['csv_file']['tmp_name'];
            
            // Fix for Mac line endings
            ini_set('auto_detect_line_endings', TRUE);
            
            $object = fopen($path, 'r');
            
            // Skip Header
            fgetcsv($object);

            $imported_count = 0;
            $skipped_count = 0;

            while (($row = fgetcsv($object)) !== FALSE) {
                // Check if row is empty or has insufficient columns
                if (count($row) < 3 || empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    continue;
                }

                $title = trim($row[0]);
                $start_date = trim($row[1]); // Expected format: YYYY-MM-DD HH:MM
                $end_date = trim($row[2]);   // Expected format: YYYY-MM-DD HH:MM
                $description = isset($row[3]) ? trim($row[3]) : '';

                // Validate Date using strtotime for flexibility
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);

                if ($start_timestamp === false || $end_timestamp === false) {
                    $skipped_count++;
                    continue;
                }

                // Format dates to match database expected format
                $formatted_start = date('Y-m-d H:i', $start_timestamp);
                $formatted_end = date('Y-m-d H:i', $end_timestamp);

                // Check for duplicates
                if ($this->Events_model->check_duplicate($title, $formatted_start, $formatted_end)) {
                    $skipped_count++;
                } else {
                    $data = [
                        'title' => $title,
                        'start_date' => $formatted_start,
                        'end_date' => $formatted_end,
                        'description' => $description,
                        'created_by' => $this->session->userdata('userid')
                    ];
                    $this->Events_model->insert_event($data);
                    $imported_count++;
                }
            }
            fclose($object);

            if ($imported_count > 0) {
                $this->session->set_flashdata('success', "$imported_count events imported successfully. $skipped_count skipped (duplicates or invalid format).");
            } else {
                $this->session->set_flashdata('error', "No events imported. $skipped_count rows skipped.");
            }
            redirect('events/manage');
        } else {
            $this->session->set_flashdata('error', 'Please upload a CSV file.');
            redirect('events/import');
        }
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
    // Download Sample CSV
    public function download_sample()
    {
        if (!in_array($this->session->userdata('role'), ['hrm', 'finance', 'super'])) {
            redirect(base_url());
        }

        $filename = 'events_sample.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        $file = fopen('php://output', 'w');
        $header = array("Title", "Start Date (YYYY-MM-DD HH:MM)", "End Date (YYYY-MM-DD HH:MM)", "Description");
        fputcsv($file, $header);

        // Add a sample row
        $sample_data = array("Team Meeting", date('Y-m-d H:i'), date('Y-m-d H:i', strtotime('+1 hour')), "Discuss project updates and milestones.");
        fputcsv($file, $sample_data);

        fclose($file);
        exit;
    }
}
