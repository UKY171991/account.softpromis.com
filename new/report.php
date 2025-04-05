<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report</title>
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
  <h3 class="mb-4">Report</h3>
  <?php
<?php
include 'inc/auth.php';
include 'inc/config.php';

$filter_month = $_GET['month'] ?? '';
$filter_year = $_GET['year'] ?? '';
$filter_type = $_GET['type'] ?? 'income';

$table = $filter_type === 'expenditure' ? 'expenditures' : 'income';

$query = "SELECT * FROM $table WHERE 1";
if ($filter_month) $query .= " AND MONTH(date) = '$filter_month'";
if ($filter_year) $query .= " AND YEAR(date) = '$filter_year'";

$result = $conn->query($query);
?>
<!-- Report Filter Form and Table Display -->
?>
</div>
</body>
</html>
