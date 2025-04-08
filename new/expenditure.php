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
      overflow-x: auto; /* Enable horizontal scrolling */
      max-width: 100%; /* Ensure it doesn't exceed the container width */
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
      scrollbar-width: thin; /* For Firefox */
      scrollbar-color: #dee2e6 #f8f9fa; /* For Firefox */
    }
    .table-responsive::-webkit-scrollbar {
      height: 8px; /* Horizontal scrollbar height */
    }
    .table-responsive::-webkit-scrollbar-thumb {
      background-color: #dee2e6;
      border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-track {
      background-color: #f8f9fa;
    }
    .table {
      white-space: nowrap; /* Prevent text wrapping */
      margin: 0;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem; /* Smaller font size for the entire table */
    }
    .table th, .table td {
      text-align: center; /* Center-align content */
      vertical-align: middle;
      padding: 0.75rem; /* Adjusted padding for better spacing */
      border-bottom: 1px solid #dee2e6;
    }
    .table th {
      background-color: #f8f9fa; /* Light gray background for headers */
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      font-size: 0.8rem; /* Slightly smaller font size */
    }
    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }
    .badge {
      font-size: 0.75rem; /* Smaller font size */
      padding: 0.3rem 0.5rem; /* Compact padding */
      border-radius: 0.3rem; /* Rounded corners */
      display: inline-flex;
      align-items: center;
      gap: 0.2rem; /* Space between icon and text */
    }
    .badge.bg-success {
      background-color: #198754;
      color: #ffffff;
    }
    .badge.bg-danger {
      background-color: #dc3545;
      color: #ffffff;
    }
    .table td .btn {
      padding: 0.3rem 0.6rem; /* Compact button padding */
      font-size: 0.75rem; /* Smaller font size for buttons */
      border-radius: 0.3rem; /* Rounded corners */
      display: inline-flex;
      align-items: center;
      gap: 0.3rem; /* Space between icon and text */
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

        <div class="table-responsive" style="overflow-x: auto; max-width: 100%; height: auto;">
          <table id="expenditureTable" class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>SL No.</th>
                <th>Invoice Number</th> <!-- New Column -->
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
                <th>Status</th> <!-- New Column -->
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                  $sl_no = 1;
                  while ($row = $result->fetch_assoc()) {
                      // Format the date to dd-mm-yyyy
                      $formatted_date = date("d-m-Y", strtotime($row['date']));
                      
                      // Generate Invoice Number
                      $invoice_number = "EXP-" . str_pad($row['id'], 5, "0", STR_PAD_LEFT);

                      // Determine the status badge
                      $status = ($row['balance'] == 0) 
                          ? "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Paid</span>" 
                          : "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> Pending</span>";

                      echo "<tr>";
                      echo "<td>" . $sl_no++ . "</td>";
                      echo "<td>" . htmlspecialchars($invoice_number) . "</td>"; // Invoice Number
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['paid'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td>" . $status . "</td>"; // Status Badge
                      echo "<td>
                              <a href='edit-expenditure.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary' title='Edit'><i class='bi bi-pencil'></i></a>
                              <a href='delete-expenditure.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' title='Delete' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='bi bi-trash'></i></a>
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