<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Auth Controller for API authentication
 * 
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property User_model $User_model
 * @property PushToken_model $PushToken_model
 */
class Auth extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login()
    {
        // Get raw input data to debug
        $rawInput = file_get_contents('php://input');

        // Try multiple methods to get the data
        $postData = $this->input->post();
        $jsonData = json_decode($rawInput, true);

        // Determine which data source to use
        $requestData = !empty($postData) ? $postData : (!empty($jsonData) ? $jsonData : []);

        // Get username and password from the combined data
        $username = isset($requestData['username']) ? $requestData['username'] : null;
        $password = isset($requestData['password']) ? $requestData['password'] : null;
        $push_token = isset($requestData['push_token']) ? $requestData['push_token'] : null;
        $device_type = isset($requestData['device_type']) ? $requestData['device_type'] : null;

        if (!$username || !$password) {
            return $this->send_response([
                'status' => false,
                'message' => 'Username and password are required - Debug: ' .
                    'Username: ' . ($username ? 'Yes' : 'No') .
                    ', Password: ' . ($password ? 'Yes' : 'No') .
                    ', Raw input: ' . $rawInput .
                    ', POST data: ' . json_encode($postData) .
                    ', JSON data: ' . json_encode($jsonData)
            ], 400);
        }

        $user = $this->User_model->logindata($username);

        if (empty($user)) {
            return $this->send_response([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = $user[0];

        if ($user['status'] != 1) {
            return $this->send_response([
                'status' => false,
                'message' => 'Account is blocked'
            ], 403);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->send_response([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate JWT token
        $token = generate_jwt_token($user);

        // Save push token if provided
        if (!empty($push_token)) {
            $this->load->model('PushToken_model');
            $result = $this->PushToken_model->saveToken($user['id'], $push_token, $device_type);
        }

        return $this->send_response([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'usertype' => $user['usertype'],
                    'staff_name' => $user['staff_name'] ?? null
                ]
            ]
        ]);
    }

    public function refresh_token()
    {
        // Generate new token using current user data
        $token = generate_jwt_token((array)$this->user);

        return $this->send_response([
            'status' => true,
            'data' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * Update device push token
     */
    public function update_push_token()
    {
        $push_token = $this->input->post('push_token');
        $device_type = $this->input->post('device_type') ?? 'android';

        if (empty($push_token)) {
            return $this->send_response([
                'status' => false,
                'message' => 'Push token is required'
            ], 400);
        }

        $this->load->model('PushToken_model');
        $token_id = $this->PushToken_model->saveToken($this->user->user_id, $push_token, $device_type);

        if ($token_id) {
            return $this->send_response([
                'status' => true,
                'message' => 'Push token updated successfully'
            ]);
        } else {
            return $this->send_response([
                'status' => false,
                'message' => 'Failed to update push token'
            ], 500);
        }
    }

    /**
     * Remove device push token (logout from device)
     */
    public function remove_push_token()
    {
        $push_token = $this->input->post('push_token');

        if (empty($push_token)) {
            return $this->send_response([
                'status' => false,
                'message' => 'Push token is required'
            ], 400);
        }

        $this->load->model('PushToken_model');
        $result = $this->PushToken_model->deactivateToken($push_token);

        if ($result) {
            return $this->send_response([
                'status' => true,
                'message' => 'Push token removed successfully'
            ]);
        } else {
            return $this->send_response([
                'status' => false,
                'message' => 'Failed to remove push token'
            ], 500);
        }
    }
}
