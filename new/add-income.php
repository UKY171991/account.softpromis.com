<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Income</title>
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
  <h3 class="mb-4">Add Income</h3>
  <?php
<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = $_POST['amount'];
    $received = $_POST['received'];
    $balance = $amount - $received;

    $stmt = $conn->prepare("INSERT INTO income (date, name, category, subcategory, amount, received, balance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $received, $balance);
    $stmt->execute();
    header("Location: income.php?success=1");
    exit;
}
?>
<!-- Add Income Form HTML here -->
?>
</div>
</body>
</html>
