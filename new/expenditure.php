<?php
include 'inc/auth.php';
include 'inc/config.php';
$result = $conn->query("SELECT * FROM expenditures ORDER BY date DESC");
?>
<!-- Expenditure List HTML with DataTables here -->
