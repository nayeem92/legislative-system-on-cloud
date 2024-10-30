<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Repositories/BillRepository.php';

// Check if the user is an Administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

// Create a database connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Instantiate the BillRepository
$billRepo = new BillRepository($connection);

// Handle voting initiation
if (isset($_POST['bill_id'])) {
    $billId = intval($_POST['bill_id']);
    
    // Fetch the bill to ensure it exists and is in the correct state
    $bill = $billRepo->getBillById($billId);
    
    if ($bill && $bill['status'] === 'Under Review') {
        // Update the status to 'Voting'
        $success = $billRepo->updateBillStatus($billId, 'Voting');
        
        if ($success) {
            $message = "Voting has been successfully initiated for the bill: " . htmlspecialchars($bill['title']);
        } else {
            $message = "Failed to initiate voting. Please try again.";
        }
    } else {
        $message = "Bill is either not found or not in the 'Under Review' status.";
    }
}

// Fetch all bills that are in 'Under Review' status for selection
$pendingBills = $billRepo->getBillsByStatus('Under Review');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initiate Voting</title>
</head>
<body>
    <h1>Initiate Voting for a Bill</h1>
    
    <?php if (isset($message)): ?>
        <p><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>
    
    <form method="post" action="initiate_voting.php">
        <label for="bill_id">Select a Bill to Initiate Voting:</label>
        <select name="bill_id" id="bill_id" required>
            <?php foreach ($pendingBills as $bill): ?>
                <option value="<?= $bill['bill_id']; ?>"><?= htmlspecialchars($bill['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Initiate Voting</button>
    </form>
    
    <a href="dashboard.php">Back to Dashboard</a> <!-- Back to Dashboard Link -->

</body>
</html>
