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

// Fetch data from the income table
$sql = "SELECT id, date, name, category, subcategory, amount, received, balance FROM income";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Income</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    /* General Body Styling */
    body {
      background: linear-gradient(135deg, #f9f9f9, #e9ecef);
      font-family: 'Roboto', sans-serif;
    }

    /* Sidebar Styling */
    .sidebar {
      height: 100vh;
      width: 250px;
      background: linear-gradient(135deg, #343a40, #23272b);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding: 1.5rem 1rem;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }
    .sidebar h4 {
      font-weight: bold;
    }
    .sidebar .nav-link {
      color: #ffffff;
      font-size: 1rem;
      padding: 0.75rem 1rem;
      border-radius: 0.3rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease-in-out;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.15);
      color: #f8f9fa;
    }

    /* Top Navbar */
    .top-navbar {
      margin-left: 250px;
      background: linear-gradient(135deg, #ffffff, #f8f9fa); /* Subtle gradient */
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 0.5rem 1.5rem; /* Reduced padding for a compact look */
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .top-navbar h4 {
      font-size: 1.15rem; /* Slightly smaller font size */
      font-weight: bold;
      color: #495057;
      margin: 0;
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

    .top-navbar .bi-bell {
      font-size: 1.3rem; /* Slightly smaller icon size */
      color: #495057;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .top-navbar .bi-bell:hover {
      color: #0d6efd; /* Hover effect */
    }

    .dropdown-menu {
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      margin-top: 1rem;
      padding: 2rem;
      background-color: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table-responsive {
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
    }

    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem; /* Smaller font size for the entire table */
    }

    .table th {
      background-color: #f1f1f1;
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      padding: 0.75rem; /* Adjusted padding for smaller headers */
      font-size: 0.75rem; /* Reduced font size for table headers */
      border-bottom: 2px solid #dee2e6;
      text-align: center; /* Center-align header content */
    }

    .table td {
      padding: 0.5rem; /* Adjusted padding for table cells */
      font-size: 0.85rem; /* Smaller font size for table data */
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }

    .table .btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.75rem; /* Smaller font size for buttons */
    }

    .table .btn-primary {
      background-color: #0d6efd;
      border: none;
      transition: background-color 0.3s ease;
    }

    .table .btn-primary:hover {
      background-color: #0b5ed7;
    }

    .table .btn-danger {
      background-color: #dc3545;
      border: none;
      transition: background-color 0.3s ease;
    }

    .table .btn-danger:hover {
      background-color: #bb2d3b;
    }

    /* Buttons */
    .btn-success {
      background-color: #198754;
      border: none;
      transition: all 0.3s ease-in-out;
    }
    .btn-success:hover {
      background-color: #157347;
    }

    /* Dropdown Button Styling */
    .dropdown-toggle {
      background-color: #ffffff;
      border: 1px solid #ced4da;
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      color: #495057;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease-in-out;
    }
    .dropdown-toggle:hover {
      background-color: #f1f1f1;
      color: #343a40;
    }
    .dropdown-menu {
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }
      .top-navbar {
        margin-left: 0;
      }
      .main-content {
        margin-left: 0;
      }
    }

    /* Action Buttons */
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
      color: #ffffff;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .table td .btn-primary:hover {
      background-color: #0b5ed7;
      transform: scale(1.05); /* Slight zoom effect */
    }

    .table td .btn-danger {
      background-color: #dc3545;
      border: none;
      color: #ffffff;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .table td .btn-danger:hover {
      background-color: #bb2d3b;
      transform: scale(1.05); /* Slight zoom effect */
    }

    /* Ensure Action Buttons are in a Single Line */
    .action-column {
      display: flex;
      justify-content: center; /* Center the buttons horizontally */
      align-items: center; /* Align the buttons vertically */
      gap: 0.5rem; /* Add space between the buttons */
    }

    .action-column .btn {
      padding: 0.3rem 0.6rem; /* Compact button padding */
      font-size: 0.75rem; /* Smaller font size for buttons */
      border-radius: 0.3rem; /* Rounded corners */
      display: inline-flex; /* Ensure buttons stay inline */
      align-items: center; /* Align icon and text vertically */
      gap: 0.3rem; /* Space between icon and text */
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h4 class="text-white mb-4">Account Panel</h4>
      <ul class="nav flex-column">
        <li><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link active"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="w-100">
      <!-- Top Navbar -->
      <div class="top-navbar">
        <h4>Income Records</h4>
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-bell" title="Notifications"></i>
          <div class="dropdown">
            <a href="#" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Content Area -->
      <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5>Income Records</h5>
          <a href="add-income.php" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Add New Income</a>
        </div>

        <div class="table-responsive">
          <table id="incomeTable" class="table table-hover">
            <thead>
              <tr>
                <th>SL No.</th>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Received Amount</th>
                <th>Balance</th>
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
                      
                      echo "<tr>";
                      echo "<td>" . $sl_no++ . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['received'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td class='action-column'>
                              <a href='edit-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i> Edit</a>
                              <a href='delete-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='bi bi-trash'></i> Delete</a>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
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
      $('#incomeTable').DataTable({
        responsive: true,
        pageLength: 10,
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

<?php<?php
// Database connection
$host = "localhost"; // Replace with your database host
$username = "u820431346_new_account"; // Replace with your database username
$password = "9g/?fYqP+"; // Replace with your database password
$database = "u820431346_new_account"; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database)<div class="table-responsive">
  <table id="incomeTable" class="table table-bordered">
    <thead>
      <tr>
        <th>SL No.</th>
        <th>Date</th>
        <th>Name</th>
        <th>Category</th>
        <th>Sub-category</th>
        <th>Total Amount</th>
        <th>Received Amount</th>
        <th>Balance</th>
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
              
              echo "<tr>";
              echo "<td>" . $sl_no++ . "</td>";
              echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['category']) . "</td>";
              echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
              echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
              echo "<td>₹" . number_format($row['received'], 2) . "</td>";
              echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
              echo "<td>
                      <a href='edit-income.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                      <a href='delete-income.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the income table
$sql = "SELECT id, date, name, category, subcategory, amount, received, balance FROM income";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Income</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    /* General Body Styling */
    body {
      background: linear-gradient(135deg, #f9f9f9, #e9ecef);
      font-family: 'Roboto', sans-serif;
    }

    /* Sidebar Styling */
    .sidebar {
      height: 100vh;
      width: 250px;
      background: linear-gradient(135deg, #343a40, #23272b);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      padding: 1.5rem 1rem;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }
    .sidebar h4 {
      font-weight: bold;
    }
    .sidebar .nav-link {
      color: #ffffff;
      font-size: 1rem;
      padding: 0.75rem 1rem;
      border-radius: 0.3rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.3s ease-in-out;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.15);
      color: #f8f9fa;
    }

    /* Top Navbar */
    .top-navbar {
      margin-left: 250px;
      background: linear-gradient(135deg, #ffffff, #f8f9fa); /* Subtle gradient */
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Subtle border */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 0.5rem 1.5rem; /* Reduced padding for a compact look */
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .top-navbar h4 {
      font-size: 1.15rem; /* Slightly smaller font size */
      font-weight: bold;
      color: #495057;
      margin: 0;
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

    .top-navbar .bi-bell {
      font-size: 1.3rem; /* Slightly smaller icon size */
      color: #495057;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .top-navbar .bi-bell:hover {
      color: #0d6efd; /* Hover effect */
    }

    .dropdown-menu {
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      margin-top: 1rem;
      padding: 2rem;
      background-color: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table-responsive {
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
    }

    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem; /* Smaller font size for the entire table */
    }

    .table th {
      background-color: #f1f1f1;
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      padding: 0.75rem; /* Adjusted padding for smaller headers */
      font-size: 0.75rem; /* Reduced font size for table headers */
      border-bottom: 2px solid #dee2e6;
      text-align: center; /* Center-align header content */
    }

    .table td {
      padding: 0.5rem; /* Adjusted padding for table cells */
      font-size: 0.85rem; /* Smaller font size for table data */
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }

    .table .btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.75rem; /* Smaller font size for buttons */
    }

    .table .btn-primary {
      background-color: #0d6efd;
      border: none;
      transition: background-color 0.3s ease;
    }

    .table .btn-primary:hover {
      background-color: #0b5ed7;
    }

    .table .btn-danger {
      background-color: #dc3545;
      border: none;
      transition: background-color 0.3s ease;
    }

    .table .btn-danger:hover {
      background-color: #bb2d3b;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h4 class="text-white mb-4">Account Panel</h4>
      <ul class="nav flex-column">
        <li><a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="income.php" class="nav-link active"><i class="bi bi-currency-rupee"></i> Income</a></li>
        <li><a href="expenditure.php" class="nav-link"><i class="bi bi-wallet2"></i> Expenditure</a></li>
        <li><a href="report.php" class="nav-link"><i class="bi bi-bar-chart"></i> Reports</a></li>
        <li><a href="client.php" class="nav-link"><i class="bi bi-person-lines-fill"></i> Clients</a></li>
        <li><a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="w-100">
      <!-- Top Navbar -->
      <div class="top-navbar">
        <h4>Income Records</h4>
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-bell" title="Notifications"></i>
          <div class="dropdown">
            <a href="#" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Content Area -->
      <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5>Income Records</h5>
          <a href="add-income.php" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Add New Income</a>
        </div>

        <div class="table-responsive">
          <table id="incomeTable" class="table table-hover">
            <thead>
              <tr>
                <th>SL No.</th>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Received Amount</th>
                <th>Balance</th>
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
                      
                      echo "<tr>";
                      echo "<td>" . $sl_no++ . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['received'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td class='action-column'>
                              <a href='edit-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i> Edit</a>
                              <a href='delete-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='bi bi-trash'></i> Delete</a>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
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
      $('#incomeTable').DataTable({
        responsive: true,
        pageLength: 10,
        language
// Close the database connection
$conn->close();
?>