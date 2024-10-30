<?php

namespace Src\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Models/User.php'; // Ensure this is the correct path to your User model

use Repositories\UserRepository;

class AuthController
{
    private $userRepository;

    public function __construct($connection)
    {
        // Initialize the UserRepository with the database connection
        $this->userRepository = new UserRepository($connection);
    }

    // User login function
    public function login($username, $password)
    {
        // Fetch user by username using UserRepository
        $user = $this->userRepository->getUserByUsername($username);

        // Debugging: Check the fetched user data
        // if ($user) {
        //     echo '<pre>'; print_r($user); echo '</pre>';
        //     echo 'Input Password: ' . $password . '<br>';
        //     echo 'Stored Password Hash: ' . $user['password_hash'] . '<br>';
        // }

        // Check if user exists and verify the password using password_verify
        if ($user && password_verify($password, $user['password_hash'])) {
            // Password is correct, return user information
            return $user;
        }

        // Authentication failed
        return false;
    }

    // User logout function
    public function logout()
    {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();
    }
}
