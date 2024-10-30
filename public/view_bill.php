<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Create BillController instance
$billController = new \Src\Controllers\BillController($connection);

// Fetch the bill details based on the provided ID
if (isset($_GET['id'])) {
    $billId = $_GET['id'];
    $bill = $billController->getBillById($billId);

    if (!$bill) {
        echo "Bill not found.";
        exit();
    }
} else {
    // Redirect to the appropriate dashboard based on user role
    switch ($_SESSION['role']) {
        case 'Member of Parliament':
            header('Location: mpDashboard.php');
            break;
        case 'Administrator':
            header('Location: adminDashboard.php');
            break;
        case 'Reviewer':
            header('Location: reviewerDashboard.php');
            break;
        default:
            header('Location: login.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bill</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
        <h1 class="text-3xl font-bold mb-4">View Bill</h1>

        <p class="text-lg"><strong>Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
        <p class="text-lg"><strong>Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>
        <p class="text-lg"><strong>Status:</strong> <?php echo htmlspecialchars($bill['status']); ?></p>

        <div class="mt-6">
            <?php
            // Provide different back buttons depending on user role
            if ($_SESSION['role'] === 'Member of Parliament') {
                echo '<a href="mpDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            } elseif ($_SESSION['role'] === 'Administrator') {
                echo '<a href="adminDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            } elseif ($_SESSION['role'] === 'Reviewer') {
                echo '<a href="reviewerDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            }
            ?>
        </div>
    </div>

</body>

</html>