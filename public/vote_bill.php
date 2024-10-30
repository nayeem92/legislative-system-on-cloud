<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';
require_once '../src/Repositories/VoteRepository.php'; // New repository for votes

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$voteRepository = new \Repositories\VoteRepository($connection); // New repository

$billId = $_GET['id'];
$bill = $billController->getBillById($billId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vote = $_POST['vote'];
    $mpId = $_SESSION['user_id'];
    
    // Store the vote using VoteRepository
    $success = $voteRepository->storeVote($billId, $mpId, $vote);

    if ($success) {
        $_SESSION['message'] = "Your vote has been successfully recorded.";
    } else {
        $_SESSION['message'] = "Failed to record your vote. Please try again.";
    }
    
    // Redirect back to the MP dashboard
    header('Location: mpDashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote on Bill</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4">Vote on Bill</h1>

        <p class="mb-2"><strong>Title:</strong> <?php echo htmlspecialchars($bill['title']); ?></p>
        <p class="mb-2"><strong>Description:</strong> <?php echo htmlspecialchars($bill['description']); ?></p>
        <form method="POST" class="space-y-4">
            <div>
                <label for="vote" class="block text-lg font-medium">Your Vote:</label>
                <select name="vote" id="vote" required class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="For">For</option>
                    <option value="Against">Against</option>
                    <option value="Abstain">Abstain</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded">Submit Vote</button>
        </form>

        <div class="mt-4">
            <a href="mpDashboard.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
