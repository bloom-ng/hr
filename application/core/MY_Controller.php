<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base controller for the application
 * 
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Router $router
 * @property CI_Config $config
 * @property User_model $User_model
 * @property PushToken_model $PushToken_model
 */
class MY_Controller extends CI_Controller
{
    // ... existing code ...
}

/**
 * API Controller for handling API requests
 * 
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Router $router
 * @property CI_Config $config
 * @property User_model $User_model
 * @property PushToken_model $PushToken_model
 */
class API_Controller extends MY_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('jwt');

        // Set JSON response header
        $this->output->set_content_type('application/json');

        // Skip auth check for login endpoint
        if ($this->router->fetch_method() !== 'login') {
            $this->_authenticate_request();
        }
    }

    protected function _authenticate_request()
    {
        $token = $this->input->get_request_header(config_item('jwt_token_header'));

        if (!$token) {
            $this->_send_unauthorized_response('No token provided');
            return;
        }

        // Remove 'Bearer ' from token if present
        $token = str_replace('Bearer ', '', $token);

        $decoded = validate_jwt_token($token);
        if (!$decoded) {
            $this->_send_unauthorized_response('Invalid token');
            return;
        }

        $this->user = $decoded->data;
    }

    protected function _send_unauthorized_response($message = 'Unauthorized')
    {
        $this->output->set_status_header(401);
        $this->output->set_output(json_encode([
            'status' => false,
            'message' => $message
        ]));
        die();
    }

    protected function send_response($data, $status_code = 200)
    {
        $this->output->set_status_header($status_code);
        $this->output->set_output(json_encode($data));
    }
}
