<?php

namespace App\Controllers;
use App\Models\UserModel as User;

class UserController
{
    private $model;

    public function __construct()
    {
        $this->model = new User;
    }

    public function register($data)
    {
        $user = $this->model->auth($data, $login = false);
        if($user) {
            echo json_encode([
                'error' => true,
                'message' => 'You have already an account try to log in.'
            ]);
        }else {
            $options = [
                'const' => 12
            ];
            //hash password
            $password = password_hash($data['password'], PASSWORD_BCRYPT, $options);
            $data['password'] = $password;
            $this->model->store($data);
            echo json_encode([
                'message' => 'Account created successfully.'
            ]);
        }
    }

    public function login($data)
    {
        $user = $this->model->auth($data, $login = true);
        if(!$user) {
            echo json_encode([
                'error' => true,
                'message' => 'These credentials do not match any of our records.'
            ]);
        }else if(password_verify($data['password'], $user['password'])){
            unset($user['password']);
            echo json_encode([
                'user' => $user,
            ]);
        }else {
            echo json_encode([
                'error' => true,
                'message' => 'These credentials do not match any of our records.'
            ]);
        }
    }

    public function logout($data)
    {
        if(!$data['api_key'] || empty($data['api_key'])) {
            http_response_code(401);
            echo json_encode([
                'error' => true,
                'message' => 'unauthenticated'
            ]);
        }else if(!$this->model->checkIfApiKeyIsValid($data['api_key'], $data['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'error' => true,
                'message' => 'unauthenticated'
            ]);
        }else {
            $this->model->signout($data['api_key'], $data['user_id']);
            echo json_encode([
                'message' => 'Logout successfully'
            ]);
        }
    }
}