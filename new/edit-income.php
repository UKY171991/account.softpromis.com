<?php
// Database connection
include 'inc/config.php'; // Include the database connection file

// Fetch income details for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    $sql = "SELECT * FROM income WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $income = $result->fetch_assoc();

    if (!$income) {
        header("Location: income.php?error=Income record not found");
        exit();
    }
} else {
    header("Location: income.php?error=No income ID provided");
    exit();
}

// Handle form submission for updating income
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = floatval($_POST['amount']);
    $received = floatval($_POST['received']);
    $balance = $amount - $received;

    // Update income record in the database
    $sql = "UPDATE income SET date = ?, name = ?, category = ?, subcategory = ?, amount = ?, received = ?, balance = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdddi", $date, $name, $category, $subcategory, $amount, $received, $balance, $id);

    if ($stmt->execute()) {
        header("Location: income.php?message=Income record updated successfully");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Income</title>
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
    }
    .top-navbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      padding: 1rem 2rem;
    }
    .form-container {
      margin: 2rem auto;
      background: white;
      padding: 2rem;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
      <li><a href="income.php" class="nav-link active"><i class="bi bi-currency-rupee"></i> Income</a></li>
      <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
      <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
      <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
      <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="main-content w-100">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Edit Income</h4>
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
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($income['date']); ?>" required>
          </div>
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($income['name']); ?>" required>
          </div>
          <div class="col-md-6">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-select" required>
              <option value="Consulting" <?php echo $income['category'] === 'Consulting' ? 'selected' : ''; ?>>Consulting</option>
              <option value="Services" <?php echo $income['category'] === 'Services' ? 'selected' : ''; ?>>Services</option>
              <option value="Products" <?php echo $income['category'] === 'Products' ? 'selected' : ''; ?>>Products</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="subcategory" class="form-label">Sub-category</label>
            <select id="subcategory" name="subcategory" class="form-select" required>
              <option value="IT Services" <?php echo $income['subcategory'] === 'IT Services' ? 'selected' : ''; ?>>IT Services</option>
              <option value="Marketing" <?php echo $income['subcategory'] === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
              <option value="Sales" <?php echo $income['subcategory'] === 'Sales' ? 'selected' : ''; ?>>Sales</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="amount" class="form-label">Total Amount (₹)</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($income['amount']); ?>" required>
          </div>
          <div class="col-md-4">
            <label for="received" class="form-label">Received Amount (₹)</label>
            <input type="number" class="form-control" id="received" name="received" value="<?php echo htmlspecialchars($income['received']); ?>" required>
          </div>
          <div class="col-md-4">
            <label for="balance" class="form-label">Balance Amount (₹)</label>
            <input type="number" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($income['balance']); ?>" readonly>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Update Income</button>
          <a href="income.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('received').addEventListener('input', updateBalance);
  document.getElementById('amount').addEventListener('input', updateBalance);

  function updateBalance() {
    const total = parseFloat(document.getElementById('amount').value) || 0;
    const received = parseFloat(document.getElementById('received').value) || 0;
    const balance = total - received;
    document.getElementById('balance').value = balance;
  }
</script>
</body>
</html>