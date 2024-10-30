<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Repositories/BillRepository.php';

// Check if the user is an Administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

// Create a new database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Instantiate the BillRepository to handle bill data
$billRepo = new BillRepository($connection); // Use $connection instead of $conn

// Fetch all bills
$bills = $billRepo->getAllBills();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Bills</title>
</head>
<body>
    <h1>List of Bills</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bills)): ?>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?= $bill['bill_id']; ?></td>
                        <td><?= htmlspecialchars($bill['title']); ?></td>
                        <td><?= htmlspecialchars($bill['description']); ?></td>
                        <td><?= htmlspecialchars($bill['author_id']); ?></td>
                        <td><?= htmlspecialchars($bill['status']); ?></td>
                        <td>
                            <a href="view_bill.php?bill_id=<?= $bill['bill_id']; ?>">View</a>
                            <?php if ($bill['status'] === 'Under Review'): ?>
                                <a href="initiate_voting.php?bill_id=<?= $bill['bill_id']; ?>">Initiate Voting</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No bills found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
