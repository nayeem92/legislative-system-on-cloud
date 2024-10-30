<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);

// Fetch the bill details
if (isset($_GET['id'])) {
    $billId = $_GET['id'];
    $bill = $billController->getBillById($billId);

    if (!$bill) {
        echo "Bill not found.";
        exit();
    }
} else {
    header('Location: reviewerDashboard.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestedTitle = $_POST['suggested_title'];
    $suggestedDescription = $_POST['suggested_description'];
    $comments = $_POST['comments'];
    $reviewerId = $_SESSION['user_id'];

    // Insert the suggested amendment into the database
    $query = "INSERT INTO amendments (bill_id, reviewer_id, suggested_title, suggested_description, comments) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("iisss", $billId, $reviewerId, $suggestedTitle, $suggestedDescription, $comments);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Amendment suggested successfully!</p>";
    } else {
        echo "<p style='color: red;'>Failed to suggest the amendment.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest Amendment</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center p-8">

    <h1 class="text-3xl font-bold mb-6">Suggest Amendment</h1>
    <p class="text-lg mb-4"><strong>Bill Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
    <p class="text-lg mb-6"><strong>Current Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <label for="suggested_title" class="block text-sm font-semibold mb-2">Suggested Title:</label>
        <input type="text" name="suggested_title" id="suggested_title" required
            class="w-full p-3 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">

        <label for="suggested_description" class="block text-sm font-semibold mb-2">Suggested Description:</label>
        <textarea name="suggested_description" id="suggested_description" required
            class="w-full p-3 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>

        <label for="comments" class="block text-sm font-semibold mb-2">Comments:</label>
        <textarea name="comments" id="comments"
            class="w-full p-3 mb-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>

        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded transition duration-200">
            Submit Amendment
        </button>
    </form>

    <a href="reviewerDashboard.php" class="mt-4 text-blue-500 hover:text-blue-700">Back to Dashboard</a>

</body>

</html>