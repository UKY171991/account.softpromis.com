<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Client</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-family: 'Roboto', sans-serif;
    }

    /* Sidebar Styling */
    .sidebar {
      height: 100vh;
      background-color: #343a40;
      color: #ffffff;
      transition: width 0.3s;
    }
    .sidebar:hover {
      width: 270px;
    }
    .sidebar .nav-link {
      color: #ffffff;
      transition: color 0.3s, background-color 0.3s;
    }
    .sidebar .nav-link.active {
      background-color: #495057;
      font-weight: bold;
    }
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    /* Main Content Styling */
    .main-content {
      margin-left: 250px;
      padding: 2rem;
    }
    .main-content h3 {
      font-weight: bold;
      color: #495057;
    }

    /* Form Styling */
    form {
      background-color: #ffffff;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .form-label {
      font-weight: bold;
      color: #495057;
    }
    .form-control {
      border-radius: 0.5rem;
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    .btn-primary {
      border-radius: 0.5rem;
    }
    .btn-secondary {
      border-radius: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sidebar {
        position: absolute;
        width: 100%;
        height: auto;
        z-index: 1030;
      }
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3 position-fixed" style="width: 250px;">
      <h4>Account Panel</h4>
      <hr>
      <ul class="nav nav-pills flex-column mb-auto">
        <li><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link active"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content w-100">
      <h3 class="mb-4">Add New Client</h3>
      <form action="#" method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
          </div>
          <div class="col-md-6">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
          <button type="submit" class="btn btn-primary w-100 me-2">Submit</button>
          <a href="clients.php" class="btn btn-secondary w-100 ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>