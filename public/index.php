<?php
session_start();

// Check if the user is already logged in and redirect based on the user role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Administrator') {
        header("Location: adminDashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'Member of Parliament') {
        header("Location: mpDashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'Reviewer') {
        header("Location: reviewerDashboard.php");
        exit();
    }
} 

// If the user is not logged in, redirect to the login page
header("Location: login.php");
exit();

?>

