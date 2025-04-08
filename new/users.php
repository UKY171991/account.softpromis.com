<?php
// Database connection
include 'inc/config.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
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
    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 0.875rem; /* Smaller font size for the entire table */
    }
    .table th {
      background-color: #f8f9fa;
      text-transform: uppercase;
      font-weight: bold;
      color: #495057;
      padding: 0.75rem;
      font-size: 0.85rem; /* Slightly larger font size for headers */
      border-bottom: 2px solid #dee2e6;
    }
    .table td {
      padding: 0.75rem;
      font-size: 0.85rem;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }
    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: background-color 0.3s ease;
    }
    .table td .btn {
      padding: 0.4rem 0.8rem;
      font-size: 0.75rem;
      border-radius: 0.3rem;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }
    .badge {
      font-size: 0.7rem; /* Smaller font size for badges */
      padding: 0.3rem 0.5rem; /* Adjusted padding for badges */
      border-radius: 0.3rem;
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
      <h4 class="mb-0">User Management</h4>
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
        <h5>Users</h5>
        <a href="add-user.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add User</a>
      </div>

      <?php
      if (isset($_GET['message'])) {
          echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
      }
      if (isset($_GET['error'])) {
          echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
      }
      ?>

      <div class="table-responsive">
        <table id="userTable" class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>SL No.</th>
              <th>Username</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Fetch users from the database
            $sql = "SELECT id, username, name, email, role, status, created_at FROM users"; // Include the 'status' column
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $sl_no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $sl_no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";

                    // Display status dynamically
                    if ($row['status'] === 'Active') {
                        echo "<td><span class='badge bg-success'>Active</span></td>";
                    } else {
                        echo "<td><span class='badge bg-danger'>Inactive</span></td>";
                    }

                    echo "<td>
                            <a href='edit-user.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                            <a href='include/delete-user.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
            }

            $conn->close();
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
    $('#userTable').DataTable({
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