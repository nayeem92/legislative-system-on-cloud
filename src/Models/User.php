<?php

namespace Models;

class User {
    private $user_id;
    private $username;
    private $password;
    private $role;
    private $email;

    public function __construct($user_id, $username, $password, $role, $email) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password; // Not hashed for now
        $this->role = $role;
        $this->email = $email;
    }

    // Getters
    public function getUserId() {
        return $this->user_id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getEmail() {
        return $this->email;
    }

    // Additional methods related to user can be added here
}
?>
