<?php

namespace App\Models;

class UserModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function createUser($username, $password, $email)
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password); // Hash in fase di registrazione
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
