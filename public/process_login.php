<?php
session_start();
require_once '../src/Config/database.php'; 
require_once '../src/Repositories/UserRepository.php';
require_once '../src/Models/User.php';

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve username and password from POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Create an instance of UserRepository with the database connection
$userRepo = new UserRepository($connection);

// Authenticate the user
$user = $userRepo->authenticate($username, $password);

if ($user) {
    // Store user information in session
    $_SESSION['user_id'] = $user->getUserId();
    $_SESSION['username'] = $user->getUsername();
    $_SESSION['role'] = $user->getRole();

    // If "Remember Me" is checked, set cookies for 30 days
    if (isset($_POST['remember_me'])) {
        setcookie('username', $username, time() + (30 * 24 * 60 * 60), "/"); // 30 days
    } else {
        // Clear the cookie if "Remember Me" is not checked
        setcookie('username', '', time() - 3600, "/");
    }

    // Redirect to the appropriate dashboard based on user role
    if ($_SESSION['role'] === 'Administrator') {
        header("Location: adminDashboard.php");
    } elseif ($_SESSION['role'] === 'Reviewer') {
        header("Location: reviewerDashboard.php");
    } elseif ($_SESSION['role'] === 'Member of Parliament') {
        header("Location: mpDashboard.php");
    }
    exit();
} else {
    // Redirect back to login with an error message
    header("Location: login.php?error=Invalid username or password");
    exit();
}

?>
