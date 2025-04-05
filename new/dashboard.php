<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
  </style>
</head>
<body>
<div class="container">
  <h3 class="mb-4">Dashboard</h3>
  <?php
<?php
include 'inc/auth.php';
include 'inc/config.php';

$income = $conn->query("SELECT SUM(received) AS total FROM income")->fetch_assoc()['total'] ?? 0;
$expenditure = $conn->query("SELECT SUM(paid) AS total FROM expenditures")->fetch_assoc()['total'] ?? 0;
$pending = $conn->query("SELECT SUM(balance) AS total FROM income WHERE balance > 0")->fetch_assoc()['total'] ?? 0;
?>
<!-- Dashboard Cards and Chart.js setup here -->
?>
</div>
</body>
</html>
