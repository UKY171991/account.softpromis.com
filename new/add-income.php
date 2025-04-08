<?php
// Database connection
include 'inc/config.php'; // Include the database connection file

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = floatval($_POST['total_amount']);
    $received = floatval($_POST['received_amount']);
    $balance = $amount - $received;

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO income (date, name, category, subcategory, amount, received, balance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $received, $balance);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Income entry added successfully.</div>";
    } else {
      $message= "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Income</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #343a40;
    }
    .sidebar .nav-link {
      color: #ffffff;
    }
    .sidebar .nav-link.active {
      background-color: #495057;
    }
    .main-content {
      margin-left: 250px;
      padding: 2rem;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3 text-white position-fixed" style="width: 250px;">
      <h4 class="text-white">Account Panel</h4>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li><a href="dashboard.php" class="nav-link "><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link active"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <div class="main-content w-100">
      <h3 class="mb-4">Add New Income</h3>

      <?php $message; ?>
      <form action="#" method="POST">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
          </div>
          <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="col-md-4">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
          </div>

          <div class="col-md-6">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" required>
          </div>
          <div class="col-md-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-select" required>
              <option selected disabled>Choose...</option>
              <option>Consulting</option>
              <option>Services</option>
              <option>Products</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="subcategory" class="form-label">Sub-category</label>
            <select id="subcategory" name="subcategory" class="form-select" required>
              <option selected disabled>Choose...</option>
              <option>IT</option>
              <option>Marketing</option>
              <option>Sales</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="total_amount" class="form-label">Total Amount (₹)</label>
            <input type="number" class="form-control" id="total_amount" name="total_amount" required>
          </div>
          <div class="col-md-4">
            <label for="received_amount" class="form-label">Received Amount (₹)</label>
            <input type="number" class="form-control" id="received_amount" name="received_amount" required>
          </div>
          <div class="col-md-4">
            <label for="balance_amount" class="form-label">Balance Amount (₹)</label>
            <input type="number" class="form-control" id="balance_amount" name="balance_amount" readonly>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="income.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('received_amount').addEventListener('input', updateBalance);
    document.getElementById('total_amount').addEventListener('input', updateBalance);

    function updateBalance() {
      const total = parseFloat(document.getElementById('total_amount').value) || 0;
      const received = parseFloat(document.getElementById('received_amount').value) || 0;
      const balance = total - received;
      document.getElementById('balance_amount').value = balance;
    }
  </script>
</body>
</html>