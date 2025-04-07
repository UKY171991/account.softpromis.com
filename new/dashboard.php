<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Dashboard</title>
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
    .dashboard-card {
      border-radius: 1rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .top-navbar {
      margin-left: 250px;
      background: linear-gradient(135deg, #ffffff, #f8f9fa); /* Subtle gradient */
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 0.3rem 1.5rem; /* Reduced top and bottom padding */
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-navbar .navbar-brand {
      font-size: 1.15rem; /* Slightly smaller font size */
      font-weight: bold;
      color: #495057;
      margin: 0;
    }
    .top-navbar .btn {
      font-size: 0.85rem;
      padding: 0.4rem 0.8rem; /* Compact button padding */
      border-radius: 0.3rem;
    }
    .top-navbar .btn-success {
      background-color: #198754;
      border: none;
      transition: background-color 0.3s ease;
    }
    .top-navbar .btn-success:hover {
      background-color: #157347;
    }
    .top-navbar .btn-danger {
      background-color: #dc3545;
      border: none;
      transition: background-color 0.3s ease;
    }
    .top-navbar .btn-danger:hover {
      background-color: #bb2d3b;
    }
    .top-navbar .bi-bell {
      font-size: 1.3rem; /* Slightly smaller icon size */
      color: #495057;
      cursor: pointer;
      transition: color 0.3s ease;
    }
    .top-navbar .bi-bell:hover {
      color: #0d6efd; /* Hover effect */
    }
    .top-navbar .dropdown a {
      color: #495057;
      text-decoration: none;
      font-size: 0.95rem; /* Slightly smaller font size */
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: color 0.3s ease;
    }
    .top-navbar .dropdown a:hover {
      color: #0d6efd; /* Hover effect */
    }
    .dropdown-menu {
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    canvas {
      background-color: white;
      border-radius: 1rem;
      padding: 1rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
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
        <li><a href="dashboard.php" class="nav-link active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <div class="main-content w-100">
      <!-- Top Navbar -->
      <nav class="navbar top-navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1">Dashboard</span>

          <div class="d-flex align-items-center gap-2">
            <a href="#" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Add Income</a>
            <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-dash-circle"></i> Add Expenditure</a>
          </div>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="bi bi-bell"></i></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> Admin
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Dashboard Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Income</h5>
              <h3 class="text-success">₹1,25,000</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Expenditure</h5>
              <h3 class="text-danger">₹98,500</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending Payments</h5>
              <h3 class="text-warning">₹26,500</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Income (This Month)</h5>
              <h3 class="text-success">₹52,000</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Expenditure (This Month)</h5>
              <h3 class="text-danger">₹41,300</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending (This Month)</h3>
              <h3 class="text-warning">₹10,700</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Graphs Section -->
      <div class="row g-4">
        <div class="col-md-6">
          <h5 class="mb-3">Monthly Income Trend</h5>
          <canvas id="incomeChart" height="300"></canvas>
        </div>
        <div class="col-md-6">
          <h5 class="mb-3">Monthly Expenditure Trend</h5>
          <canvas id="expenditureChart" height="300"></canvas>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const incomeChart = new Chart(document.getElementById('incomeChart'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Income',
          data: [20000, 25000, 30000, 28000, 32000, 36000],
          borderColor: 'green',
          backgroundColor: 'rgba(0,128,0,0.1)',
          tension: 0.3,
          fill: true
        }]
      }
    });

    const expenditureChart = new Chart(document.getElementById('expenditureChart'), {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Expenditure',
          data: [15000, 20000, 18000, 22000, 21000, 25000],
          backgroundColor: 'rgba(220,53,69,0.7)'
        }]
      }
    });
  </script>
</body>

</html>
