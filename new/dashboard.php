<?php
include 'inc/auth.php';
include 'inc/config.php';

$income = $conn->query("SELECT SUM(received) AS total FROM income")->fetch_assoc()['total'] ?? 0;
$expenditure = $conn->query("SELECT SUM(paid) AS total FROM expenditures")->fetch_assoc()['total'] ?? 0;
$pending = $conn->query("SELECT SUM(balance) AS total FROM income WHERE balance > 0")->fetch_assoc()['total'] ?? 0;
?>
<!-- Dashboard Cards and Chart.js setup here -->
