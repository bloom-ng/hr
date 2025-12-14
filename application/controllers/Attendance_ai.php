<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_ai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Ensure user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        $data['title'] = 'Attendance AI';
        $data['page'] = 'Attendance AI';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/attendance_ai', $data);
        $this->load->view('admin/footer');
    }

    public function analyze() {
        if (!isset($_FILES['attendance_file']['name']) || $_FILES['attendance_file']['error'] != 0) {
            echo json_encode(['error' => 'Please upload a valid CSV file.']);
            return;
        }

        $file_path = $_FILES['attendance_file']['tmp_name'];
        
        // --- LOGIC PORTED FROM PYTHON ---
        // 1. Read CSV Safely (Try tab then comma)
        $rows = [];
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            // Read first chunk to detect BOM/Encoding
            $content = file_get_contents($file_path);
            
            // Check for UTF-16LE BOM or pattern
            if (mb_detect_encoding($content, 'UTF-16LE', true) || substr($content, 0, 2) === "\xFF\xFE") {
                // Convert to UTF-8
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
                // Save temp file
                $file_path = tempnam(sys_get_temp_dir(), 'utf8_csv');
                file_put_contents($file_path, $content);
                fclose($handle);
                $handle = fopen($file_path, "r");
            } else {
                // Rewind if standard
                rewind($handle);
            }

            // Check delimiter again on UTF-8 content
            $line = fgets($handle); 
            rewind($handle);
            $delimiter = (strpos($line, "\t") !== false) ? "\t" : ",";

            // Get headers
            $headers = fgetcsv($handle, 0, $delimiter);
            
            // Normalize headers
            $normalized_headers = array_map(function($h) {
                // Remove BOM if present (UTF-8) and extra spaces
                $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
                return strtolower(trim(str_replace([' ', "\t", '"', "'"], '_', $h)));
            }, $headers);

            // Find key columns
            $name_idx = array_search('name', $normalized_headers);
            $time_idx = array_search('time', $normalized_headers); // Common in logs
            $date_idx = array_search('date', $normalized_headers); // Sometimes separate
            $datetime_idx = array_search('datetime', $normalized_headers); // Sometimes combined

            // Fallback for column detection
            if ($name_idx === false) {
                 // Try finding any column with 'name' in it
                 foreach($normalized_headers as $idx => $val) {
                     if (strpos($val, 'name') !== false) {
                         $name_idx = $idx;
                         break;
                     }
                 }
                 // If still false, assume index 3 (standard BioMetric log "Name") or index 1
                 if ($name_idx === false) $name_idx = 3; 
            }

            while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                // Map based on indices
                $row = [];
                $row['name'] = $data[$name_idx] ?? ($data[0] ?? 'Unknown'); // Fallback to 0 if 3 fails
                
                // Construct Datetime
                $raw_time = '';
                if ($datetime_idx !== false && isset($data[$datetime_idx])) {
                    $raw_time = $data[$datetime_idx];
                } elseif ($time_idx !== false && isset($data[$time_idx])) {
                     if ($date_idx !== false && isset($data[$date_idx])) {
                        $raw_time = $data[$date_idx] . ' ' . $data[$time_idx];
                     } else {
                        // Try parsing as datetime even if label is time
                        $raw_time = $data[$time_idx];
                     }
                } else {
                    // Fallback: look for a column that looks like a date/time (e.g. contains :)
                    foreach ($data as $cell) {
                         if (strpos($cell, ':') !== false && (strpos($cell, '/') !== false || strpos($cell, '-') !== false)) {
                             $raw_time = $cell;
                             break;
                         }
                    }
                }
                
                // Attempt to parse date
                $timestamp = strtotime($raw_time);
                if (!$timestamp) continue; // Skip invalid rows

                $row['timestamp'] = $timestamp;
                $row['date_str'] = date('Y-m-d', $timestamp);
                $row['time_str'] = date('H:i:s', $timestamp);
                $row['hour'] = (int)date('H', $timestamp);
                $row['minute'] = (int)date('i', $timestamp);
                $rows[] = $row;
            }
            fclose($handle);
        }

        // 2. Process Data (Dedup: Keep earliest per day)
        $daily_entries = [];
        foreach ($rows as $r) {
            $key = $r['name'] . '_' . $r['date_str'];
            if (!isset($daily_entries[$key])) {
                $daily_entries[$key] = $r;
            } else {
                if ($r['timestamp'] < $daily_entries[$key]['timestamp']) {
                    $daily_entries[$key] = $r;
                }
            }
        }

        // 3. Apply Immunity Rules
        $fifteen_min_immunity = ["IFY ANYAMAH", "ELIZABETH IN", "WILLIAMS MON", "CHIDI"];
        $thirty_min_immunity = ["EMMA OBI"];
        
        $stats = []; 

        foreach ($daily_entries as $entry) {
            $name_upper = strtoupper(trim($entry['name']));
            
            // Determine immunity
            $cutoff_hour = 9;
            $cutoff_min = 0;
            
            foreach ($fifteen_min_immunity as $immune) {
                if (strpos($name_upper, $immune) !== false) {
                    $cutoff_min = 15;
                    break;
                }
            }
            if ($cutoff_min == 0) { 
                 foreach ($thirty_min_immunity as $immune) {
                    if (strpos($name_upper, $immune) !== false) {
                        $cutoff_min = 30;
                        break;
                    }
                }
            }

            // Check Late
            $is_late = false;
            // Late if hour > 9 OR (hour == 9 AND minute > cutoff)
            if ($entry['hour'] > $cutoff_hour) {
                $is_late = true;
            } elseif ($entry['hour'] == $cutoff_hour && $entry['minute'] > $cutoff_min) {
                $is_late = true;
            }

            // Aggregate
            if (!isset($stats[$entry['name']])) {
                $stats[$entry['name']] = ['present' => 0, 'late' => 0, 'name' => $entry['name']];
            }
            $stats[$entry['name']]['present']++;
            if ($is_late) {
                $stats[$entry['name']]['late']++;
            }
        }

        // 4. Summarize
        $total_staff = count($stats);
        $total_records = count($rows); 
        $total_late = 0;
        $output_list = [];

        foreach ($stats as $s) {
            $total_late += $s['late'];
            $rate = ($s['present'] > 0) ? ($s['late'] / $s['present']) : 0;
            $s['lateness_rate'] = round($rate * 100, 1); // Percentage
            $output_list[] = $s;
        }

        // Helper for sorting
        usort($output_list, function($a, $b) {
            return $b['lateness_rate'] <=> $a['lateness_rate']; // Descending Lateness
        });

        $top_late = array_slice($output_list, 0, 5);
        $top_punctual = array_reverse(array_slice($output_list, -5)); 

        // Save Analysis Result to Session
        $summary_text = "Analysis Summary:\n";
        foreach ($output_list as $s) {
            $summary_text .= "- {$s['name']}: Present {$s['present']} days, Late {$s['late']} times.\n";
        }
        $this->session->set_userdata('attendance_analysis_context', $summary_text);

        echo json_encode([
            'success' => true,
            'summary' => [
                'total_staff' => $total_staff,
                'total_late' => $total_late
            ],
            'top_late' => $top_late,
            'top_punctual' => $top_punctual,
            'all_staff' => $output_list // Return full list
        ]);
    }

    public function ask() {
        $question = $this->input->post('question');
        $context = $this->session->userdata('attendance_analysis_context');

        if (!$context) {
            echo json_encode(['answer' => 'Please upload and analyze a file first.']);
            return;
        }

        if (!$question) {
             echo json_encode(['answer' => 'Please ask a question.']);
             return;
        }

        $api_key = $this->config->item('groq_api_key');
        if (!$api_key) {
             echo json_encode(['answer' => 'Error: GROQ_API_KEY not set in config.']);
             return;
        }

        $messages = [
            [
                "role" => "system",
                "content" => "You are an HR assistant. Answer questions based on this attendance summary:\n" . $context
            ],
            [
                "role" => "user",
                "content" => $question
            ]
        ];

        $answer = $this->_get_groq_answer($messages, $api_key);
        echo json_encode(['answer' => $answer]);
    }



    private function _get_groq_answer($messages, $api_key) {
        $url = "https://api.groq.com/openai/v1/chat/completions";
        $data = [
            "model" => "llama-3.1-8b-instant",
            "messages" => $messages,
            "temperature" => 0.1
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $api_key,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code != 200) {
            return "Error calling AI: API responded with code $http_code. Raw: $response";
        }

        $json = json_decode($response, true);
        return $json['choices'][0]['message']['content'] ?? 'No answer provided.';
    }
}
