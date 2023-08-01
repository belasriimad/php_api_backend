<?php

namespace App\Models;
use App\Database\Database as DB;
use PDO;


class UserModel
{
    private $conn;

    public function __construct()
    {
        $database = new DB;
        $this->conn = $database->connect();
    }

    public function store($data)
    {
        $stmt = $this->conn->prepare('INSERT INTO users(name, email, password)
            VALUES (:name, :email, :password)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->execute();
    }

    public function auth($data, $login) {
        $email = $data['email'];
        $stmt = $this->conn->prepare("SELECT * FROM users
            WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && $login) {
            $user['api_key'] = $this->createApiKey($user['id']);
        }
        return $user;
    }

    public function signout($api_key, $user_id)
    {
        $this->removeApiKey($api_key, $user_id);
    }

    public function createApiKey($user_id)
    {
        $api_key = bin2hex(random_bytes(16));
        $stmt = $this->conn->prepare('INSERT INTO api_keys(user_id, api_key)
            VALUES (:user_id, :api_key)');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':api_key', $api_key);
        $stmt->execute();
        return $api_key;
    }

    public function removeApiKey($api_key, $user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM api_keys
            WHERE user_id = :user_id AND api_key = :api_key");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':api_key', $api_key);
        $stmt->execute();
    }

    public function checkIfApiKeyIsValid($api_key, $user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM api_keys
            WHERE user_id = :user_id AND api_key = :api_key");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':api_key', $api_key);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
}