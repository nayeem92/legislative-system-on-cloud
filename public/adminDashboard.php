<?php
session_start();

// Include the database and controller files
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';
require_once __DIR__ . '/../src/Repositories/VoteRepository.php'; 

use Src\Controllers\BillController;

// Check if the user is logged in and if the user has the Administrator role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController and VoteRepository
$billController = new BillController($connection);
$voteRepository = new \Repositories\VoteRepository($connection); // New repository

$bills = $billController->getAllBills(); // Fetch all bills for the admin to review

// Handle delete request
if (isset($_GET['delete_id'])) {
    $billController->deleteBill($_GET['delete_id']);
    header('Location: adminDashboard.php'); // Redirect to refresh the page after deletion
    exit();
}

// Handle viewing voting results
if (isset($_GET['view_results_id'])) {
    // Display voting results for this bill
    $billId = $_GET['view_results_id'];
    $votingResults = $voteRepository->getVotingResults($billId);
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col items-center p-8 min-h-screen">
    <div class="container mx-auto px-4">

        <h2 class="text-2xl font-semibold mb-4">All Bills</h2>
        <div class="mb-4">
            <a href="view_amendments.php" class="text-blue-500 hover:text-blue-700">View Pending Amendments</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="py-4 px-6 text-center">Title</th>
                        <th class="py-4 px-6 text-center">Description</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                        <th class="py-4 px-6 text-center">Export</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill): ?>
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="py-4 px-6 text-center"><?php echo htmlspecialchars($bill['title']); ?></td>
                            <td class="py-4 px-6 text-center"><?php echo htmlspecialchars($bill['description']); ?></td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-block px-3 py-1 rounded-md text-sm font-semibold 
                                    <?php echo $bill['status'] === 'Voting' ? 'bg-green-200 text-green-800' : 
                                           ($bill['status'] === 'Draft' ? 'bg-gray-200 text-gray-800' : 'bg-yellow-200 text-yellow-800'); ?>">
                                    <?php echo htmlspecialchars($bill['status']); ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" 
                                       class="inline-flex items-center justify-center bg-blue-500 text-white rounded-md px-3 py-1 hover:bg-blue-600 transition duration-200">View</a>
                                    
                                    <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" class="bg-yellow-500 inline-flex  items-center justify-center text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition duration-200">Edit</a>
                                    
                                    <a href="adminDashboard.php?delete_id=<?php echo $bill['bill_id']; ?>" 
                                       class="inline-flex items-center justify-center bg-red-500 text-white rounded-md px-3 py-1 hover:bg-red-600 transition duration-200" 
                                       onclick="return confirm('Are you sure you want to delete this bill?');">Delete</a>
                                    
                                    <?php if ($bill['status'] === 'Voting'): ?>
                                        <a href="adminDashboard.php?view_results_id=<?php echo $bill['bill_id']; ?>" 
                                           class="inline-flex items-center justify-center bg-green-500 text-white rounded-md px-3 py-1 hover:bg-green-600 transition duration-200">View Voting Results</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="export_to_xml.php?bill_id=<?php echo $bill['bill_id']; ?>" 
                                   class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 transition duration-200">Download</a>
                            </td>
                        </tr>
                        <!-- Display Voting Results if available -->
                        <?php if (isset($votingResults) && $_GET['view_results_id'] == $bill['bill_id']): ?>
                            <tr>
                                <td colspan="5" class="bg-gray-100 p-4">
                                    <h3 class="text-lg font-semibold">Voting Results for: <?php echo htmlspecialchars($bill['title']); ?></h3>
                                    <p><strong>For:</strong> <?php echo $votingResults['For']; ?></p>
                                    <p><strong>Against:</strong> <?php echo $votingResults['Against']; ?></p>
                                    <p><strong>Abstain:</strong> <?php echo $votingResults['Abstain']; ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
// Include the footer
include 'footer.php';
?>
