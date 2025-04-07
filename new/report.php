<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
    .table-responsive {
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
    }
    .table th {
      background-color: #f1f1f1;
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      padding: 0.75rem;
      font-size: 0.85rem;
      border-bottom: 2px solid #dee2e6;
      text-align: center;
    }
    .table td {
      padding: 0.75rem;
      font-size: 0.85rem;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }
    .table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .table tbody tr:hover {
      background-color: #f1f1f1;
      transition: background-color 0.3s ease;
    }
    .table td:last-child {
      text-align: center;
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
      <li><a href="report.php" class="nav-link active"><i class="bi bi-bar-chart"></i> Reports</a></li>
      <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
      <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="main-content w-100">
    <!-- Top Navbar -->
    <div class="top-navbar d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Reports</h4>
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
      <form class="row g-3 mb-4">
        <div class="col-md-3">
          <label for="from_date" class="form-label">From Date</label>
          <input type="date" class="form-control" id="from_date">
        </div>
        <div class="col-md-3">
          <label for="to_date" class="form-label">To Date</label>
          <input type="date" class="form-control" id="to_date">
        </div>
        <div class="col-md-3">
          <label for="type" class="form-label">Type</label>
          <select id="type" class="form-select">
            <option selected value="all">All</option>
            <option value="income">Income</option>
            <option value="expenditure">Expenditure</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">Generate Report</button>
        </div>
      </form>

      <div class="table-responsive">
        <table id="reportTable" class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>SL No.</th>
              <th>Date</th>
              <th>Type</th>
              <th>Name</th>
              <th>Description</th>
              <th>Category</th>
              <th>Sub-category</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>01-04-2024</td>
              <td>Income</td>
              <td>John Doe</td>
              <td>Consulting Fee</td>
              <td>Services</td>
              <td>IT</td>
              <td>₹15,000</td>
            </tr>
            <tr>
              <td>2</td>
              <td>02-04-2024</td>
              <td>Expenditure</td>
              <td>Jane Smith</td>
              <td>Office Supplies</td>
              <td>Operations</td>
              <td>Stationery</td>
              <td>₹5,000</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    $('#reportTable').DataTable({
      responsive: true,
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50, 100]
    });
  });
</script>
</body>
</html>