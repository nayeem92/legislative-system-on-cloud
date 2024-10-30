<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Legislative System'; ?></title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-gray-800 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-8">
            <h1 class="text-3xl font-extrabold">Legislative System</h1>
            <div class="flex items-center gap-4">
                <p class="text-lg">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></span>!</p>
                <a href="process_logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-200">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content Container Start -->
    <main class="flex-grow container mx-auto px-8 py-6">
