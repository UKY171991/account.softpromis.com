<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Client</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
      max-width: 600px;
    }
  </style>
</head>
<body>
<div class="container">
  <h3 class="mb-4">Add Client</h3>

  <?php
  include 'inc/auth.php';
  include 'inc/config.php';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $name = ucfirst(trim($_POST['name']));
      $phone = trim($_POST['phone']);
      $email = trim($_POST['email']);
      $address = ucfirst(trim($_POST['address']));

      $stmt = $conn->prepare("INSERT INTO clients (name, phone, email, address) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $phone, $email, $address);
      if ($stmt->execute()) {
          echo '<div class="alert alert-success">Client added successfully.</div>';
      } else {
          echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
      }
  }
  ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Client Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone Number</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100">Add Client</button>
  </form>
</div>
</body>
</html>
