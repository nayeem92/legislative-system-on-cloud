<?php
session_start();

// Include the database and controller files
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

// Set a page title (optional)
$pageTitle = "Reviewer Dashboard";

use Src\Controllers\BillController; // Use the correct namespace

// Check if the user is logged in and if the user has the Reviewer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController
$billController = new BillController($connection);
$bills = $billController->getAllBills(); // Fetch all bills for the reviewer to review

// Include the header
include __DIR__ . '/header.php';
?>

<!-- Main content (inside <main> container) -->
<h2 class="text-3xl font-semibold mb-6 text-gray-700">All Bills</h2>
<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead>
            <tr class="bg-gray-800 text-white">
                <th class="py-3 px-6">Title</th>
                <th class="py-3 px-6">Description</th>
                <th class="py-3 px-6">Status</th>
                <th class="py-3 px-6">Actions</th>
                <th class="py-3 px-6">Export</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills as $bill): ?>
                <tr class="border-b hover:bg-gray-100 transition duration-200">
                    <td class="py-4 px-6"><?php echo htmlspecialchars($bill['title']); ?></td>
                    <td class="py-4 px-6"><?php echo htmlspecialchars($bill['description']); ?></td>
                    <td class="py-4 px-6"><?php echo htmlspecialchars($bill['status']); ?></td>
                    <td class="py-4 px-6">
                        <div class="flex space-x-2">
                            <a href="suggest_amendment.php?id=<?php echo $bill['bill_id']; ?>" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition duration-200">Amend</a>
                            <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition duration-200">View</a>
                            <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition duration-200">Edit</a>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <a href="export_to_xml.php?bill_id=<?php echo $bill['bill_id']; ?>" class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 transition duration-200">Download</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
// Include the footer
include __DIR__ . '/footer.php';
?>
