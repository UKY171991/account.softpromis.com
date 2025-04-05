<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Users</title>
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
  <h3 class="mb-4">Users</h3>
  <?php
<?php
include 'inc/auth.php';
include 'inc/config.php';
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!-- Users List HTML with DataTables here -->
?>
</div>
</body>
</html>
