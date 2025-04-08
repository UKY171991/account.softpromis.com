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

$sql = "SELECT id, date, name, category, subcategory, amount, received, balance FROM income";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Income Records</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <style>
    body {
      background: linear-gradient(135deg, #f9f9f9, #e9ecef);
      font-family: 'Roboto', sans-serif;
    }
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
    .top-navbar {
      margin-left: 250px;
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 0.5rem 1.5rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-navbar h4 {
      font-size: 1.15rem;
      font-weight: bold;
      color: #495057;
      margin: 0;
    }
    .top-navbar .dropdown a {
      color: #495057;
      text-decoration: none;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: color 0.3s ease;
    }
    .top-navbar .dropdown a:hover,
    .top-navbar .bi-bell:hover {
      color: #0d6efd;
    }
    .top-navbar .bi-bell {
      font-size: 1.3rem;
      color: #495057;
      cursor: pointer;
    }
    .main-content {
      margin-left: 250px;
      margin-top: 1rem;
      padding: 2rem;
    }
    .table-responsive {
      border-radius: 0.5rem;
      overflow-x: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
    }
    .table {
      white-space: nowrap;
      font-size: 0.875rem;
    }
    .table th, .table td {
      text-align: center;
      vertical-align: middle;
      padding: 0.75rem;
      border-bottom: 1px solid #dee2e6;
    }
    .table th {
      background-color: #f8f9fa;
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      font-size: 0.8rem;
    }
    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }
    .action-column {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
    }
    .action-column .btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.75rem;
      border-radius: 0.3rem;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: all 0.3s ease;
    }
    .btn-primary {
      background-color: #0d6efd;
      border: none;
      color: #fff;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
      transform: scale(1.05);
    }
    .btn-danger {
      background-color: #dc3545;
      border: none;
      color: #fff;
    }
    .btn-danger:hover {
      background-color: #bb2d3b;
      transform: scale(1.05);
    }
    .btn-success {
      background-color: #198754;
      border: none;
    }
    .btn-success:hover {
      background-color: #157347;
    }
    .badge {
      font-size: 0.75rem;
      padding: 0.2rem 0.4rem;
      border-radius: 0.3rem;
      display: inline-flex;
      align-items: center;
      gap: 0.2rem;
    }
    .badge.bg-success {
      background-color: #198754;
    }
    .badge.bg-danger {
      background-color: #dc3545;
    }
    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }
      .top-navbar,
      .main-content {
        margin-left: 0;
      }
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

      <!-- Content -->
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
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Received</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                  $sl_no = 1;
                  while ($row = $result->fetch_assoc()) {
                      $formatted_date = date("d-m-Y", strtotime($row['date']));
                      $status = ($row['balance'] == 0) 
                          ? "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Paid</span>" 
                          : "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> Pending</span>";

                      echo "<tr>";
                      echo "<td>{$sl_no}</td>";
                      echo "<td>INV-" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['received'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td>" . $status . "</td>";
                      echo "<td class='action-column'>
                              <a href='edit-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i></a>
                              <a href='delete-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='bi bi-trash'></i></a>
                            </td>";
                      echo "</tr>";
                      $sl_no++;
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

<?php
$conn->close();
?>
