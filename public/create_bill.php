<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

// Include necessary files
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

// Handle form submission for creating a new bill
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id']; // Set the logged-in MP's user ID as the author

    // Initialize the BillController
    $billController = new \Src\Controllers\BillController($connection);

    // Set the initial status of the bill
    $status = 'Draft';


    if ($billController->createBill($title, $description, $author_id, $status)) {
        // Redirect to the MP Dashboard if successful
        header('Location: mpDashboard.php');
        exit();
    } else {
        // Display error if bill creation fails
        echo "Failed to create the bill. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Bill</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Create New Bill</h1>

        <!-- Form to Create a New Bill -->
        <form method="post" action="" class="space-y-4">
            <div>
                <label for="title" class="block text-lg font-medium">Title:</label>
                <input type="text" name="title" id="title" required class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div>
                <label for="description" class="block text-lg font-medium">Description:</label>
                <textarea name="description" id="description" required class="mt-1 p-2 border border-gray-300 rounded-md w-full"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded">Create Bill</button>
        </form>

        <!-- Link back to MP Dashboard -->
        <div class="mt-4">
            <a href="mpDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        </div>
    </div>

</body>

</html>