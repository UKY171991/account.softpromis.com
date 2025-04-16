<?php
include 'inc/auth.php'; // Include the authentication file to check user login status
// Database connection
include 'inc/config.php'; // Include the database connection file

// Fetch expenditure details for editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input
    $sql = "SELECT * FROM expenditures WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $expenditure = $result->fetch_assoc();

    if (!$expenditure) {
        header("Location: expenditure.php?error=Expenditure record not found");
        exit();
    }

    // Convert the date to dd-mm-yyyy format for display
    $expenditure['date'] = date('d-m-Y', strtotime($expenditure['date']));
} else {
    header("Location: expenditure.php?error=No expenditure ID provided");
    exit();
}

// Handle form submission for updating expenditure
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convert date from dd-mm-yyyy to yyyy-mm-dd for database storage
    $date = DateTime::createFromFormat('d-m-Y', $_POST['date'])->format('Y-m-d');
    $name = ucfirst(trim($_POST['name']));
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = floatval($_POST['amount']);
    $paid = floatval($_POST['paid']);
    $balance = $amount - $paid;

    // Update expenditure record in the database
    $sql = "UPDATE expenditures SET date = ?, name = ?, phone = ?, description = ?, category = ?, subcategory = ?, amount = ?, paid = ?, balance = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssdddi", $date, $name, $phone, $description, $category, $subcategory, $amount, $paid, $balance, $id);

    if ($stmt->execute()) {
        header("Location: expenditure.php?message=Expenditure record updated successfully");
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
  <title>Edit Expenditure</title>
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

    <!-- <h3 class="mb-4">Edit Expenditure</h3> -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="POST" class="form-container">
      <div class="row g-3">
        <div class="col-md-4">
          <label for="date" class="form-label">Date</label>
          <input type="text" class="form-control date-picker" id="date" name="date" value="<?php echo htmlspecialchars($expenditure['date']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($expenditure['name']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="phone" class="form-label">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($expenditure['phone']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="description" class="form-label">Description</label>
          <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($expenditure['description']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="category" class="form-label">Category</label>
          <select id="category" name="category" class="form-select" required>
            <option value="Utilities" <?php echo $expenditure['category'] === 'Utilities' ? 'selected' : ''; ?>>Utilities</option>
            <option value="Maintenance" <?php echo $expenditure['category'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
            <option value="Salaries" <?php echo $expenditure['category'] === 'Salaries' ? 'selected' : ''; ?>>Salaries</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="subcategory" class="form-label">Sub-category</label>
          <select id="subcategory" name="subcategory" class="form-select" required>
            <option value="Electricity" <?php echo $expenditure['subcategory'] === 'Electricity' ? 'selected' : ''; ?>>Electricity</option>
            <option value="Water" <?php echo $expenditure['subcategory'] === 'Water' ? 'selected' : ''; ?>>Water</option>
            <option value="Stationery" <?php echo $expenditure['subcategory'] === 'Stationery' ? 'selected' : ''; ?>>Stationery</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="amount" class="form-label">Total Amount (₹)</label>
          <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($expenditure['amount']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="paid" class="form-label">Paid Amount (₹)</label>
          <input type="number" class="form-control" id="paid" name="paid" value="<?php echo htmlspecialchars($expenditure['paid']); ?>" required>
        </div>
        <div class="col-md-4">
          <label for="balance" class="form-label">Balance Amount (₹)</label>
          <input type="number" class="form-control" id="balance" name="balance" value="<?php echo htmlspecialchars($expenditure['balance']); ?>" readonly>
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Update Expenditure</button>
        <a href="expenditure.php" class="btn btn-secondary">Cancel</a>
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
  document.getElementById('paid').addEventListener('input', updateBalance);
  document.getElementById('amount').addEventListener('input', updateBalance);

  function updateBalance() {
    const total = parseFloat(document.getElementById('amount').value) || 0;
    const paid = parseFloat(document.getElementById('paid').value) || 0;
    const balance = total - paid;
    document.getElementById('balance').value = balance;
  }
</script>
</body>
</html>