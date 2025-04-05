<?php
include 'inc/auth.php';
include 'inc/config.php';
$result = $conn->query("SELECT * FROM clients ORDER BY id DESC");
?>
<!-- Client List HTML with DataTables here -->
