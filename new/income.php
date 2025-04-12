<?php
include 'inc/auth.php'; // Include the authentication file
include 'inc/config.php'; // Include the database connection file

$sql = "SELECT id, date, name, phone, description, category, subcategory, amount, received, balance, created_at, updated_at FROM income";
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
      background-color: #f8f9fa;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      min-height: 100vh;
      background-color: #343a40;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1040;
    }
    .sidebar .nav-link {
      color: #ffffff;
      padding: 0.75rem 1rem;
    }
    .sidebar .nav-link.active {
      background-color: #495057;
    }
    .main-content {
      margin-left: 250px;
      min-height: 100vh;
      background-color: #f8f9fa;
    }
    .top-navbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      padding: 1rem 2rem;
      width: calc(100% - 250px);
      margin-left: 250px;
    }
    .table-responsive {
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-top: 1rem;
    }
    .table {
      margin: 0;
      width: 100%;
    }
    .table th {
      background-color: #f8f9fa;
      text-transform: uppercase;
      font-weight: 600;
      color: #495057;
      padding: 1rem;
      white-space: nowrap;
      border-bottom: 2px solid #dee2e6;
    }
    .table td {
      padding: 0.75rem 1rem;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }
    .table tbody tr:hover {
      background-color: #f8f9fa;
    }
    .action-column {
      white-space: nowrap;
      width: 100px;
    }
    .action-column .btn {
      padding: 0.25rem 0.5rem;
      margin: 0 0.125rem;
    }
    .badge {
      padding: 0.35rem 0.65rem;
      font-weight: 500;
    }
    .dataTables_wrapper .dataTables_length select {
      padding: 0.375rem 2.25rem 0.375rem 0.75rem;
      border-radius: 0.25rem;
      border: 1px solid #dee2e6;
    }
    .dataTables_wrapper .dataTables_filter input {
      padding: 0.375rem 0.75rem;
      border-radius: 0.25rem;
      border: 1px solid #dee2e6;
      margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0.375rem 0.75rem;
      margin: 0 0.125rem;
      border-radius: 0.25rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: #0d6efd;
      border-color: #0d6efd;
      color: white !important;
    }
    .btn-success {
      background-color: #198754;
      border-color: #198754;
      color: white;
    }
    .btn-success:hover {
      background-color: #157347;
      border-color: #146c43;
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
      <div class="top-navbar d-flex justify-content-between align-items-center">
        <h4>Income Records</h4>
        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-bell" title="Notifications"></i>
          <div class="dropdown">
            <a href="#" class="dropdown-toggle text-decoration-none text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
      <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <a href="add-income.php" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i> Add New Income</a>
        </div>

        <?php
        if (isset($_GET['message']))  { 
            echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['message']) . "</div>";
        }
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
        }
        ?>

        <div class="table-responsive">
          <table id="incomeTable" class="table table-hover">
            <thead>
              <tr>
                <th>SL No.</th>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Description</th>
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
                      $formatted_created_at = date("d-m-Y H:i:s", strtotime($row['created_at']));
                      $formatted_updated_at = date("d-m-Y H:i:s", strtotime($row['updated_at']));
                      $status = ($row['balance'] == 0) 
                          ? "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Paid</span>" 
                          : "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> Pending</span>";

                      echo "<tr>";
                      echo "<td>{$sl_no}</td>";
                      echo "<td>INV-" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
                      echo "<td>" . htmlspecialchars($formatted_date) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['received'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td>" . $status . "</td>";
                      echo "<td class='action-column'>
                              <a href='edit-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'><i class='bi bi-pencil'></i></a>
                              <a href='include/delete-income.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this record?\")'><i class='bi bi-trash'></i></a>
                            </td>";
                      echo "</tr>";
                      $sl_no++;
                  }
              } else {
                  echo "<tr><td colspan='15' class='text-center'>No records found</td></tr>";
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
