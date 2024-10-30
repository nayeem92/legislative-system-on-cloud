<?php
require_once '../src/Repositories/UserRepository.php';

class UserController {
    private $userRepository;

    public function __construct($pdo) {
        $this->userRepository = new UserRepository($pdo);
    }

    public function login($username, $password) {
        $user = $this->userRepository->findByUsername($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}
?>
