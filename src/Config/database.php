<?php
// Database configuration
define('DB_HOST', 'db'); // Uncomment this when using docker-compose
// define('DB_HOST', 'localhost'); // For using Xampp or Wampp
define('DB_USER', 'root'); // Default MySQL username
define('DB_PASSWORD', ''); // Leave password empty
define('DB_NAME', 'legislation_system_demo'); // Your database name

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
