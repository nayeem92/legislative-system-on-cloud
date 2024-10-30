<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Member of Parliament'])) {
    header('Location: login.php');
    exit();
}

// Create BillController instance
$billController = new \Src\Controllers\BillController($connection);

// Fetch all pending amendments
$query = "SELECT a.*, b.title AS bill_title, u.username AS reviewer FROM amendments a
          JOIN bills b ON a.bill_id = b.bill_id
          JOIN users u ON a.reviewer_id = u.user_id
          WHERE a.status = 'Pending'";
$result = $connection->query($query);
$amendments = $result->fetch_all(MYSQLI_ASSOC);

// Handle Accept/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amendmentId = $_POST['amendment_id'];
    $action = $_POST['action'];

    if ($action === 'Accept') {
        // Update the status of the amendment to 'Accepted'
        $query = "UPDATE amendments SET status = 'Accepted' WHERE amendment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $amendmentId);
        $stmt->execute();

        // Apply the accepted amendment to the bill
        $billController->applyAmendment($amendmentId);
    } elseif ($action === 'Reject') {
        // Update the status of the amendment to 'Rejected'
        $query = "UPDATE amendments SET status = 'Rejected' WHERE amendment_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $amendmentId);
        $stmt->execute();
    }

    // Refresh the page to show updated amendments
    header('Location: view_amendments.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Amendments</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">

    <h1 class="text-3xl font-bold mb-6">Pending Amendments</h1>

    <div class="mb-4">
        <?php if ($_SESSION['role'] === 'Member of Parliament'): ?>
            <a href="mpDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        <?php elseif ($_SESSION['role'] === 'Administrator'): ?>
            <a href="adminDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        <?php endif; ?>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700 text-left">
                    <th class="py-3 px-4 border-b">Bill Title</th>
                    <th class="py-3 px-4 border-b">Suggested Title</th>
                    <th class="py-3 px-4 border-b">Suggested Description</th>
                    <th class="py-3 px-4 border-b">Reviewer</th>
                    <th class="py-3 px-4 border-b">Comments</th>
                    <th class="py-3 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($amendments as $amendment): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($amendment['bill_title']); ?></td>
                        <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($amendment['suggested_title']); ?></td>
                        <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($amendment['suggested_description']); ?></td>
                        <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($amendment['reviewer']); ?></td>
                        <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($amendment['comments']); ?></td>
                        <td class="py-3 px-4 border-b">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="amendment_id" value="<?php echo $amendment['amendment_id']; ?>">
                                <button type="submit" name="action" value="Accept" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded transition duration-200">Accept</button>
                                <button type="submit" name="action" value="Reject" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded transition duration-200">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>