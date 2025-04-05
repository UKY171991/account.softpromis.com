<?php
include 'inc/auth.php';
include 'inc/config.php';
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!-- Users List HTML with DataTables here -->
