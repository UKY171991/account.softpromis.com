<?php
include 'inc/auth.php';
include 'inc/config.php';
$result = $conn->query("SELECT * FROM income ORDER BY date DESC");
?>
<!-- Income List HTML with DataTables here -->
