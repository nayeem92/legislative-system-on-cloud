<?php
session_start();

// Adjust the path if necessary, based on your file structure
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Config/database.php'; // Corrected path here

use Src\Controllers\AuthController; // Use the correct namespace

// Create a connection (make sure it's correct)
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
// else {
//     echo "Database connection successful.<br>"; // Debugging line
// }

$authController = new AuthController($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $authController->login($username, $password);

    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] === 'Administrator') {
            header("Location: adminDashboard.php");
        } elseif ($user['role'] === 'Reviewer') {
            header("Location: reviewerDashboard.php");
        } elseif ($user['role'] === 'Member of Parliament') {
            header("Location: mpDashboard.php");
        }
        exit();
    } else {
        echo "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center h-screen">

    <form method="POST" class="bg-white p-8 rounded-lg shadow-lg w-80">
        <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Login</h2>
        <input type="text" name="username" placeholder="Username" required
            class="w-full p-3 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
        <input type="password" name="password" placeholder="Password" required
            class="w-full p-3 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">

        <label class="flex items-center text-sm text-gray-600 mb-4">
            <input type="checkbox" name="remember_me" class="mr-2">
            Remember Me
        </label>

        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded transition duration-200">
            Login
        </button>
    </form>

</body>

</html>