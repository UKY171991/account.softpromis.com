<?php
// Database connection
$host = "localhost";
$username = "u820431346_new_account";
$password = "9g/?fYqP+";
$database = "u820431346_new_account";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $status = $conn->real_escape_string($_POST['status']);

    // Insert user into the database
    $sql = "INSERT INTO users (username, name, email, role, password, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $fullname, $email, $role, $password, $status);

    if ($stmt->execute()) {
        // Redirect to users page with success message
        header("Location: users.php?message=User added successfully");
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
  <title>Add User</title>
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
      <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
      <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
      <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
      <li><a href="users.php" class="nav-link active"><i class="bi bi-people"></i> Users</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="main-content w-100">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Add User</h4>
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

    <div class="p-4">
      <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="col-md-6">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="col-md-6">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" class="form-select" required>
              <option selected disabled>Choose Role</option>
              <option value="Admin">Admin</option>
              <option value="Manager">Manager</option>
              <option value="Staff">Staff</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
              <option value="Active" selected>Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Create User</button>
          <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>