<?php
include 'inc/auth.php'; // Include the authentication file
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

    // Convert the date to dd-mm-yyyy format for display
    $income['date'] = date('d-m-Y', strtotime($income['date']));
} else {
    header("Location: income.php?error=No income ID provided");
    exit();
}

// Handle form submission for updating income
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convert date from dd-mm-yyyy to yyyy-mm-dd for database storage
    $date = DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d');
    $name = ucfirst(trim($_POST['name']));
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = floatval($_POST['amount']);
    $received = floatval($_POST['received']);
    $balance = $amount - $received;

    // Update income record in the database
    $sql = "UPDATE income SET date = ?, name = ?, phone = ?, description = ?, category = ?, subcategory = ?, amount = ?, received = ?, balance = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssdddi", $date, $name, $phone, $description, $category, $subcategory, $amount, $received, $balance, $id);

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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/responsive.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content w-100">
    <!-- Top Navbar -->
    <?php include 'topbar.php'; // Add topbar include ?>

    <!-- <h3 class="mb-4">Edit Income</h3> -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="POST" class="form-container">
      <div class="row g-3">
        <div class="col-md-4">
          <label for="date" class="form-label">Date</label>
          <input type="text" class="form-control date-picker" id="date" name="date" value="<?php echo htmlspecialchars($income['date']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($income['name']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="phone" class="form-label">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($income['phone']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="description" class="form-label">Description</label>
          <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($income['description']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="category" class="form-label">Category</label>
          <select id="category" name="category" class="form-select" required>
            <option value="Consulting" <?php echo $income['category'] === 'Consulting' ? 'selected' : ''; ?>>Consulting</option>
            <option value="Services" <?php echo $income['category'] === 'Services' ? 'selected' : ''; ?>>Services</option>
            <option value="Products" <?php echo $income['category'] === 'Products' ? 'selected' : ''; ?>>Products</option>
          </select>
        </div>
        <div class="col-md-4">
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

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/responsive.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  // Initialize Flatpickr for date picker
  flatpickr('.date-picker', {
    dateFormat: "d-m-Y"
  });

  // Update balance amount dynamically
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