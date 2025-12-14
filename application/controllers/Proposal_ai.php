<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class Proposal_ai extends CI_Controller {

    private $software_pricing = [
        "Landing Page" => [
            "Basic" => ["price" => 800000, "desc" => "Custom design, contact form, up to 5 sections, responsive."],
            "Standard" => ["price" => 1200000, "desc" => "Includes Basic + UI/UX design, premium stock photos, basic SEO."],
            "Premium" => ["price" => 2000000, "desc" => "Includes Standard + animations, custom illustrations, advanced SEO."]
        ],
        "Dynamic Website" => [
            "Basic" => ["price" => 1500000, "desc" => "Custom design, 10 pages, CMS."],
            "Standard" => ["price" => 3000000, "desc" => "Basic + UI/UX, premium assets, advanced CMS."],
            "Premium" => ["price" => 5000000, "desc" => "Standard + animations, 3rd party APIs."]
        ],
        "E-commerce Website" => [
            "Basic" => ["price" => 2000000, "desc" => "Basic cart, checkout, payment gateway."],
            "Standard" => ["price" => 3500000, "desc" => "Basic + Advanced product mgmt, UI/UX."],
            "Premium" => ["price" => 5000000, "desc" => "Standard + API integrations, advanced SEO."]
        ],
        "Mobile App" => [
            "Basic" => ["price" => 3000000, "desc" => "Login, dashboard, backend API."],
            "Standard" => ["price" => 5000000, "desc" => "Basic + UI/UX, Push notifications."],
            "Premium" => ["price" => 9000000, "desc" => "Standard + Analytics, Advanced features."]
        ]
    ];

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url() . 'login');
        }
    }

    public function index() {
        $data['title'] = 'Proposal AI';
        $data['page'] = 'Proposal AI';
        $this->load->view('admin/header', $data);
        $this->load->view('admin/proposal_ai', $data);
        $this->load->view('admin/footer');
    }

    public function generate() {
        $client_name = $this->input->post('client_name');
        $project_title = $this->input->post('project_title');
        $tone = $this->input->post('tone');
        $manual_details = $this->input->post('details');
        
        // 1. Handle File Upload or Manual Text
        $brief_text = $manual_details;
        if (isset($_FILES['brief_file']) && $_FILES['brief_file']['error'] == 0) {
            $extracted = $this->_extract_text($_FILES['brief_file']);
            if ($extracted) {
                $brief_text = $extracted;
            }
        }

        if (!$client_name || empty(trim($brief_text))) {
            echo json_encode(['error' => 'Client Name and valid Brief (text/file) are required.']);
            return;
        }

        $api_key = $this->config->item('groq_api_key');
        if (!$api_key) return $this->_json_err('GROQ_API_KEY missing.');

        // 2. Classify / Categorize (Determine Pricing)
        // We'll ask AI to classify the brief into one of our categories + tier
        $classification = $this->_classify_project($brief_text, $api_key);
        
        // Setup Pricing
        $cat = $classification['category'] ?? 'Custom';
        $tier = $classification['tier'] ?? 'Standard';
        $price_info = $this->_get_pricing($cat, $tier);
        
        // If auto-title was requested
        if (empty($project_title)) {
            $project_title = $classification['suggested_title'] ?? "$cat Project";
        }

        // 3. Generate Proposal Content
        $prompt = "Write a professional proposal.\n" .
                  "Client: $client_name\n" .
                  "Title: $project_title\n" .
                  "Tone: $tone\n" .
                  "Brief: " . substr($brief_text, 0, 3000) . "\n" .
                  "Pricing Context: Category=$cat, Tier=$tier, Base Price={$price_info['price_formatted']}, Details={$price_info['desc']}.\n" .
                  "Output JSON with keys: executive_summary, objectives, scope, deliverables, timeline, budget_text (use the Naira price provided).";

        $messages = [
            ["role" => "system", "content" => "You are a professional proposal writer. Output strictly JSON."],
            ["role" => "user", "content" => $prompt]
        ];

        $ai_response = $this->_get_groq_json($messages, $api_key);
        if (!$ai_response) return $this->_json_err('AI Generation Failed.');

        // 4. Create DOCX
        $filename = $this->_create_docx($client_name, $project_title, $ai_response, $price_info);

        // 5. Return success
        echo json_encode([
            'success' => true,
            'download_url' => base_url('uploads/proposals/' . $filename),
            'preview_text' => $this->_generate_preview_text($ai_response, $price_info)
        ]);
    }

    private function _extract_text($file) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $tmp = $file['tmp_name'];
        
        try {
            if ($ext == 'txt') {
                return file_get_contents($tmp);
            } elseif ($ext == 'pdf') {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($tmp);
                return $pdf->getText();
            } elseif ($ext == 'docx') {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($tmp);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;
            }
        } catch (Exception $e) {
            return null; // Fail silently/gracefully
        }
        return null;
    }

    private function _classify_project($text, $api_key) {
        // Simple classification prompt
        $cats = implode(", ", array_keys($this->software_pricing));
        $prompt = "Analyze this brief: " . substr($text, 0, 1000) . "...\n" .
                  "Classify it into one of: [$cats] or 'Custom'.\n" .
                  "Determine complexity tier: Basic, Standard, or Premium.\n" .
                  "Suggest a short Project Title.\n" .
                  "Return JSON: {category, tier, suggested_title}";
        
        $messages = [
            ["role" => "system", "content" => "JSON only."],
            ["role" => "user", "content" => $prompt]
        ];
        
        $res = $this->_get_groq_json($messages, $api_key);
        return $res ?? ['category' => 'Custom', 'tier' => 'Standard', 'suggested_title' => 'Project Proposal'];
    }

    private function _get_pricing($cat, $tier) {
        if (isset($this->software_pricing[$cat][$tier])) {
            $p = $this->software_pricing[$cat][$tier];
            return [
                'price' => $p['price'],
                'price_formatted' => '₦' . number_format($p['price']),
                'desc' => $p['desc']
            ];
        }
        return [
            'price' => 0, 
            'price_formatted' => 'To be discussed', 
            'desc' => 'Custom requirements'
        ];
    }

    private function _create_docx($client, $title, $data, $price) {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Styles
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 24], ['spaceAfter' => 240]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 16, 'color' => '333333'], ['spaceBefore' => 240]);

        $section->addTitle($title, 1);
        $section->addText("Prepared for: $client", ['size' => 12]);
        $section->addText("Date: " . date('F j, Y'), ['size' => 12]);
        $section->addPageBreak();

        $headers = [
            'executive_summary' => '1. Executive Summary',
            'objectives' => '2. Objectives',
            'scope' => '3. Scope of Work',
            'deliverables' => '4. Deliverables',
            'timeline' => '5. Timeline',
        ];

        foreach ($headers as $key => $label) {
            $section->addTitle($label, 2);
            $section->addText($this->_sanitize($data[$key] ?? ''));
        }

        $section->addTitle('6. Budget & Pricing', 2);
        // Add explicit table for pricing
        $section->addText("Based on the requirements, we estimate the investment as follows:");
        
        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
        $table->addRow();
        $table->addCell(4000)->addText("Item", ['bold' => true]);
        $table->addCell(2000)->addText("Cost (NGN)", ['bold' => true]);
        
        $table->addRow();
        $table->addCell(4000)->addText($title . " (" . $price['desc'] . ")");
        $table->addCell(2000)->addText($price['price_formatted']);
        
        $section->addTextBreak();
        $section->addText($this->_sanitize($data['budget_text'] ?? ''));

        $target_dir = FCPATH . 'uploads/proposals/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $filename = 'Proposal_' . preg_replace('/[^a-zA-Z0-9]/', '_', $client) . '_' . date('Ymd_His') . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($target_dir . $filename);
        
        return $filename;
    }

    private function _generate_preview_text($data, $price) {
        // Plain text preview for the typing effect
        $txt = "";
        $txt .= "EXECUTIVE SUMMARY\n" . $this->_sanitize($data['executive_summary']) . "\n\n";
        $txt .= "DELIVERABLES\n" . $this->_sanitize($data['deliverables']) . "\n\n";
        $txt .= "BUDGET ESTIMATE\n";
        $txt .= "Total: " . $price['price_formatted'] . "\n";
        $txt .= $price['desc'];
        return $txt;
    }

    private function _sanitize($input) {
        if (is_array($input)) {
            return implode("\n", array_map(function($item) {
                return (is_string($item) || is_numeric($item)) ? $item : json_encode($item);
            }, $input));
        }
        return (string)$input;
    }

    private function _json_err($msg) {
        echo json_encode(['error' => $msg]);
        exit;
    }

    private function _get_groq_json($messages, $key) {
        $ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "model" => "llama-3.1-8b-instant",
            "messages" => $messages,
            "temperature" => 0.2,
            "response_format" => ["type" => "json_object"]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $key", "Content-Type: application/json"]);
        $res = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($res, true);
        $content = $data['choices'][0]['message']['content'] ?? null;
        return $content ? json_decode($content, true) : null;
    }
}
