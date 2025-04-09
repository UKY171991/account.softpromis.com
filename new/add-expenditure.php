<?php
// Database connection
include 'inc/config.php'; // Include the database connection file

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = floatval($_POST['total_amount']);
    $paid = floatval($_POST['paid_amount']);
    $balance = $amount - $paid;

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO expenditures (date, name, category, subcategory, amount, paid, balance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $paid, $balance);

    if ($stmt->execute()) {
        // Redirect back to the expenditure page with a success message
        header("Location: expenditure.php?message=Expenditure entry added successfully");
        exit();
    } else {
        // Redirect back to the expenditure page with an error message
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Expenditure</title>
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
    .form-container {
      background: white;
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .form-container h3 {
      margin-bottom: 1.5rem;
      font-weight: bold;
    }
    .form-container .form-label {
      font-weight: 500;
    }
    .form-container .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .form-container .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004085;
    }
    .top-navbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      padding: 1rem 2rem;
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
        <li><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link active"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <div class="main-content w-100">
      <!-- Top Navbar -->
      <div class="top-navbar d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Add Expenditure</h4>
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-bell fs-5"></i>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle fs-5 me-1"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="form-container w-100">
        <h3>Add New Expenditure</h3>
        <?php if (!empty($message)): ?>
          <?php echo $message; ?>
        <?php endif; ?>
        <form action="" method="POST">
          <div class="row g-4">
            <div class="col-md-6">
              <label for="date" class="form-label">Date</label>
              <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="col-md-6">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
            </div>
            <div class="col-md-6">
              <label for="category" class="form-label">Category</label>
              <select id="category" name="category" class="form-select" required>
                <option selected disabled>Choose...</option>
                <option>Utilities</option>
                <option>Maintenance</option>
                <option>Salaries</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="subcategory" class="form-label">Sub-category</label>
              <select id="subcategory" name="subcategory" class="form-select" required>
                <option selected disabled>Choose...</option>
                <option>Electricity</option>
                <option>Water</option>
                <option>Stationery</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="total_amount" class="form-label">Total Amount (₹)</label>
              <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Enter total amount" required>
            </div>
            <div class="col-md-4">
              <label for="paid_amount" class="form-label">Paid Amount (₹)</label>
              <input type="number" class="form-control" id="paid_amount" name="paid_amount" placeholder="Enter paid amount" required>
            </div>
            <div class="col-md-4">
              <label for="balance_amount" class="form-label">Balance Amount (₹)</label>
              <input type="number" class="form-control" id="balance_amount" name="balance_amount" readonly>
            </div>
          </div>

          <div class="mt-4 d-flex justify-content-end gap-3">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="expenditure.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('paid_amount').addEventListener('input', updateBalance);
    document.getElementById('total_amount').addEventListener('input', updateBalance);

    function updateBalance() {
      const total = parseFloat(document.getElementById('total_amount').value) || 0;
      const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
      const balance = total - paid;
      document.getElementById('balance_amount').value = balance;
    }
  </script>
</body>
</html>
