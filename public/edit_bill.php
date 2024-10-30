<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$billId = $_GET['id'];
$bill = $billController->getBillById($billId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $updated = $billController->updateBill($billId, $title, $description, $status);
    if ($updated) {
        echo "<p style='color: green;'>Bill successfully updated!</p>";
    } else {
        echo "<p style='color: red;'>Failed to update the bill.</p>";
    }

    // Reload the updated bill details
    $bill = $billController->getBillById($billId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bill</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Edit Bill</h1>

        <!-- Display bill details -->
        <p class="mb-2"><strong>Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
        <p class="mb-2"><strong>Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>
        <p class="mb-2"><strong>Status (submitted):</strong> <?php echo htmlspecialchars($bill['status']); ?></p>
        <p class="mb-4"><strong>Bill ID:</strong> <?php echo htmlspecialchars($bill['bill_id']); ?></p>

        <form method="POST" class="space-y-4">
            <div>
                <label for="title" class="block text-lg font-medium">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($bill['title']); ?>" required class="mt-1 p-2 border border-gray-300 rounded-md w-full">
            </div>

            <div>
                <label for="description" class="block text-lg font-medium">Description:</label>
                <textarea name="description" id="description" required class="mt-1 p-2 border border-gray-300 rounded-md w-full"><?php echo htmlspecialchars($bill['description']); ?></textarea>
            </div>

            <div>
                <label for="status" class="block text-lg font-medium">Status:</label>
                <select name="status" id="status" required class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <?php if ($_SESSION['role'] === 'Member of Parliament'): ?>
                        <option value="Draft" <?php if ($bill['status'] === 'Draft') echo 'selected'; ?>>Draft</option>
                        <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
                    <?php elseif ($_SESSION['role'] === 'Reviewer'): ?>
                        <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
                        <option value="Ready for Voting" <?php if ($bill['status'] === 'Ready for Voting') echo 'selected'; ?>>Ready for Voting</option>
                    <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
                        <option value="Under Review" <?php if ($bill['status'] === 'Under Review') echo 'selected'; ?>>Under Review</option>
                        <option value="Voting" <?php if ($bill['status'] === 'Voting') echo 'selected'; ?>>Voting</option>
                        <option value="Passed" <?php if ($bill['status'] === 'Passed') echo 'selected'; ?>>Passed</option>
                        <option value="Rejected" <?php if ($bill['status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                        <option value="Amended" <?php if ($bill['status'] === 'Amended') echo 'selected'; ?>>Amended</option>
                    <?php endif; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded">Update Bill</button>
        </form>

        <!-- Back to Dashboard Link -->
        <div class="mt-4">
            <?php
            if ($_SESSION['role'] === 'Member of Parliament') {
                echo '<a href="mpDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            } elseif ($_SESSION['role'] === 'Reviewer') {
                echo '<a href="reviewerDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            } elseif ($_SESSION['role'] === 'Administrator') {
                echo '<a href="adminDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>';
            }
            ?>
        </div>
    </div>

</body>

</html>
