<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if (!function_exists('generate_jwt_token')) {
    function generate_jwt_token($user_data)
    {
        $time = time();
        $token = array(
            'iat' => $time,
            'exp' => $time + (60 * 60 * 24 * 30), // 30 days expiration
            'data' => array(
                'user_id' => $user_data['id'],
                'username' => $user_data['username'],
                'role' => $user_data['role'],
                'usertype' => $user_data['usertype']
            )
        );

        return JWT::encode($token, config_item('jwt_secret_key'), 'HS256');
    }
}

if (!function_exists('validate_jwt_token')) {
    function validate_jwt_token($token)
    {
        try {
            return JWT::decode($token, new Key(config_item('jwt_secret_key'), 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}
