<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chatbot extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function ask()
    {
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $message = $this->input->post('message');
        if (empty($message)) {
            echo json_encode(['error' => 'Message is required']);
            return;
        }

        $response = $this->call_groq_api($message);
        echo json_encode(['response' => $response]);
    }

    private function call_groq_api($user_message)
    {
        $url = 'https://api.groq.com/openai/v1/chat/completions';
        
        $data = [
            'model' => 'llama-3.1-8b-instant', // Using a standard Groq model
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful HR assistant for Bloom EMS aad you go by the name Ify.'],
                ['role' => 'user', 'content' => $user_message]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->config->item('groq_api_key')
        ]);

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return "Error connecting to AI service: " . $error_msg;
        }

        curl_close($ch);

        $decoded = json_decode($result, true);
        
        if (isset($decoded['choices'][0]['message']['content'])) {
            return $decoded['choices'][0]['message']['content'];
        } else if (isset($decoded['error']['message'])) {
             return "API Error: " . $decoded['error']['message'];
        } else {
            return "Sorry, I couldn't understand that.";
        }
    }
}
