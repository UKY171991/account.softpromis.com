<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Pages that managers cannot access
$restricted_pages = ['dashboard.php', 'expenditure.php', 'add-expenditure.php', 'edit-expenditure.php'];

// Check if user is manager and trying to access restricted pages
if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'manager' && in_array($current_page, $restricted_pages)) {
    // Redirect to income page with error message
    header("Location: income.php?error=You do not have permission to access this page");
    exit();
}
?>