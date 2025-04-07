<?php
// Database connection
$host = "localhost"; // Replace with your database host
$username = "u820431346_new_account"; // Replace with your database username
$password = "9g/?fYqP+"; // Replace with your database password
$database = "u820431346_new_account"; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the expenditures table
$sql = "SELECT id, date, name, category, subcategory, amount, paid, balance, created_at FROM expenditures";
$result = $conn->query($sql);

// Debugging: Check if the query executed successfully
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Debugging: Check if rows are returned
if ($result->num_rows === 0) {
    echo "<script>console.log('No records found in the expenditures table.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expenditure List</title>
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
      font-size: 0.75rem;
      border-bottom: 2px solid #dee2e6;
      text-align: center;
    }
    .table td {
      padding: 0.5rem;
      font-size: 0.85rem;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }
    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }
    .table td .btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.75rem;
      border-radius: 0.3rem;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }
    .table td .btn-primary {
      background-color: #0d6efd;
      border: none;
      transition: background-color 0.3s ease;
    }
    .table td .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .table td .btn-danger {
      background-color: #dc3545;
      border: none;
      transition: background-color 0.3s ease;
    }
    .table td .btn-danger:hover {
      background-color: #bb2d3b;
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
        <h4 class="mb-0">Expenditure Records</h4>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5>Expenditure Records</h5>
          <a href="add-expenditure.php" class="btn btn-success btn-sm"><i class="bi bi-dash-circle"></i> Add New Expenditure</a>
        </div>

        <div class="table-responsive">
          <table id="expenditureTable" class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>SL No.</th>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                  $sl_no = 1;
                  while ($row = $result->fetch_assoc()) {
                      // Format the date and created_at to dd-mm-yyyy
                      $formatted_date = date("d-m-Y", strtotime($row['date']));
                      $formatted_created_at = date("d-m-Y", strtotime($row['created_at']));
                      
                      echo "<tr>";
                      echo "<td>" . $sl_no++ . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['paid'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_created_at) . "</td>";
                      echo "<td>
                              <a href='edit-expenditure.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                              <a href='delete-expenditure.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
              }
              ?>
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
      $('#expenditureTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          paginate: {
            next: "Next",
            previous: "Previous"
          }
        }
      });
    });
  </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>