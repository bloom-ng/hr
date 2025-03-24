<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PushToken_model extends CI_Model
{

    private $table = 'push_tokens';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Save or update a device push token
     *
     * @param int $user_id The user ID
     * @param string $token The push notification token
     * @param string $device_type The device type (ios, android, web)
     * @return int|bool The insert ID or false on failure
     */
    public function saveToken($user_id, $token, $device_type = 'android')
    {
        // Check if token already exists for this user
        $existing = $this->getTokenByUser($user_id, $token);

        if ($existing) {
            // Update existing token
            $this->db->where('id', $existing['id']);
            $this->db->update($this->table, [
                'token' => $token,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'device_type' => $device_type
            ]);
            return $existing['id'];
        } else {
            // Insert new token
            $data = [
                'user_id' => $user_id,
                'token' => $token,
                'device_type' => $device_type,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Get token by user ID and token value
     *
     * @param int $user_id The user ID
     * @param string $token The token value
     * @return array|null The token data or null if not found
     */
    public function getTokenByUser($user_id, $token)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('token', $token);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return null;
    }

    /**
     * Get all active tokens for a user
     *
     * @param int $user_id The user ID
     * @return array The tokens
     */
    public function getActiveTokensByUser($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return [];
    }

    /**
     * Deactivate a token
     *
     * @param string $token The token to deactivate
     * @return bool Success or failure
     */
    public function deactivateToken($token)
    {
        $this->db->where('token', $token);
        return $this->db->update($this->table, ['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get all active tokens
     *
     * @return array The active tokens
     */
    public function getAllActiveTokens()
    {
        $this->db->select('token');
        $this->db->where('status', 1);
        $query = $this->db->get($this->table);

        return $query->num_rows() > 0 ? $query->result_array() : [];
    }
}
