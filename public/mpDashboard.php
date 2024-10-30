<?php
session_start();

// Include the database and BillController
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

// Ensure the namespace matches that in BillController.php
use Src\Controllers\BillController;

// Check if the user is logged in as a Member of Parliament
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

// Initialize the BillController
$billController = new BillController($connection);

// Retrieve all bills to display in the dashboard
$bills = $billController->getAllBills();

// Fetch the count of pending amendments
try {
    $query = "SELECT COUNT(*) AS pending_count FROM amendments WHERE status = 'Pending'";
    $result = $connection->query($query);
    $pendingAmendmentsCount = $result->fetch_assoc()['pending_count'];
} catch (Exception $e) {
    $pendingAmendmentsCount = 0; // Default to 0 if there's an error
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .notification-badge {
            background-color: #ef4444; /* Red color */
            color: white;
            border-radius: 9999px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center p-8 h-screen w-full">

    <h2 class="text-2xl font-semibold mt-6 mb-4">All Bills for Voting</h2>
    <div class="mb-4">
        <a href="view_amendments.php" class="text-blue-500 hover:text-blue-700 mr-4">
            View Pending Amendments
        <span class="inline-flex items-center justify-center bg-red-500 text-white text-xs font-semibold ml-2 px-2.5 py-0.5 rounded-full">
            <?php echo $pendingAmendmentsCount; ?>
        </span>
        </a>
        <a href="create_bill.php" class="text-blue-500 hover:text-blue-700 ml-4">Create New Bill</a>
    </div>

    <table class="min-w-full border border-gray-300 mt-4">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="py-4 px-6 text-left">Title</th>
                <th class="py-4 px-6 text-left">Description</th>
                <th class="py-4 px-6 text-center">Status</th>
                <th class="py-4 px-6 text-center">Actions</th>
                <th class="py-4 px-6 text-center">Export</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills as $bill): ?>
                <tr class="bg-white hover:bg-gray-50">
                    <td class="py-4 px-6"><?php echo htmlspecialchars($bill['title']); ?></td>
                    <td class="py-4 px-6"><?php echo htmlspecialchars($bill['description']); ?></td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                            <?php echo $bill['status'] === 'Voting' ? 'bg-green-200 text-green-800' : 
                                   ($bill['status'] === 'Draft' ? 'bg-gray-200 text-gray-800' : 'bg-yellow-200 text-yellow-800'); ?>">
                            <?php echo htmlspecialchars($bill['status']); ?>
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <?php if ($bill['status'] === 'Voting' || $bill['status'] === 'Voting in Progress'): ?>
                        <a href="vote_bill.php?id=<?php echo $bill['bill_id']; ?>" class="bg-green-500 text-white rounded-full px-3 py-1 hover:bg-green-600 transition duration-200" style="white-space: nowrap; width: auto; display: inline-block;"> Vote Now </a>
                        <?php elseif ($bill['status'] === 'Draft' || $bill['status'] === 'Under Review'): ?>
                            <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" 
                            class="bg-yellow-500 text-white rounded-full px-3 py-1 hover:bg-yellow-600 transition duration-200"
                            style="white-space: nowrap; width: auto; display: inline-block;">
                            Edit
                            </a>
                        <?php endif; ?>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <a href="export_to_xml.php?bill_id=<?php echo $bill['bill_id']; ?>" class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 transition duration-200">Download</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Include the footer
include 'footer.php';
?>
