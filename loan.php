<?php
include 'inc/auth.php'; // Include the authentication file
include 'inc/config.php'; // Include the database connection file

// Check if user is manager and redirect if true
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager') {
    // Redirect to dashboard with error message
    header("Location: dashboard.php?error=You do not have permission to access this page");
    exit();
}

// Fetch all loans with proper date formatting
$sql = "SELECT id, DATE_FORMAT(date, '%d-%m-%Y') as formatted_date, name, category, subcategory, 
        amount, paid, balance, DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at 
        FROM loans ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loan Records</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="assets/css/responsive.css">
  <style>
    html, body {
      height: 100%;
      overflow: auto;
    }

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
      overflow-y: auto;
      height: 100vh;
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
      overflow-x: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
      padding: 1.5rem;
      margin-top: 1rem;
      scrollbar-width: thin;
      scrollbar-color: #dee2e6 #f8f9fa;
    }

    .table-responsive::-webkit-scrollbar {
      height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
      background-color: #dee2e6;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-track {
      background-color: #f8f9fa;
    }

    .table {
      white-space: nowrap;
      margin: 0;
      border-collapse: separate;
      border-spacing: 0;
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

    .action-column .btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.75rem;
    }

    .badge {
      padding: 0.5em 0.8em;
      font-weight: 500;
    }

    .badge i {
      margin-right: 0.25rem;
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
      <?php include 'topbar.php'; ?>

      <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5>Loan Records</h5>
          <a href="add-loan.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Loan
          </a>
        </div>

        <?php
        if (isset($_GET['message'])) { 
            echo "<div class='alert alert-success alert-dismissible fade show'>" . htmlspecialchars($_GET['message']) . 
                "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
        if (isset($_GET['error'])) {
            echo "<div class='alert alert-danger alert-dismissible fade show'>" . htmlspecialchars($_GET['error']) . 
                "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        }
        ?>

        <div class="table-responsive">
          <table id="loanTable" class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>SL No.</th>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub-category</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result && $result->num_rows > 0) {
                  $sl_no = 1;
                  while ($row = $result->fetch_assoc()) {
                      $status = ($row['balance'] == 0) 
                          ? "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Paid</span>" 
                          : "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> Pending</span>";

                      echo "<tr>";
                      echo "<td>{$sl_no}</td>";
                      echo "<td>LOAN-" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
                      echo "<td>" . htmlspecialchars($row['formatted_date']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['subcategory']) . "</td>";
                      echo "<td>₹" . number_format($row['amount'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['paid'], 2) . "</td>";
                      echo "<td>₹" . number_format($row['balance'], 2) . "</td>";
                      echo "<td>" . $status . "</td>";
                      echo "<td class='action-column'>
                              <a href='edit-loan.php?id=" . $row['id'] . "' class='btn btn-primary' title='Edit'>
                                <i class='bi bi-pencil'></i>
                              </a>
                              <a href='include/delete-loan.php?id=" . $row['id'] . "' class='btn btn-danger' 
                                 onclick='return confirm(\"Are you sure you want to delete this loan record?\")' title='Delete'>
                                <i class='bi bi-trash'></i>
                              </a>
                            </td>";
                      echo "</tr>";
                      $sl_no++;
                  }
              } else {
                  echo "<tr><td colspan='11' class='text-center'>No loan records found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="assets/js/responsive.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable with a slight delay
      setTimeout(function() {
          try {
            $('#loanTable').DataTable({
              // responsive: true, // Temporarily disabled
              order: [[2, 'desc']], // Sort by date column (index 2) in descending order
              lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
              // Explicit columns removed for testing
              columnDefs: [
                { targets: '_all', className: 'text-center' }, // Center all columns
                { targets: 10, orderable: false } // Make Action column (index 10) not sortable
              ]
            });
          } catch (error) {
            console.error("DataTable initialization error:", error);
            // Optionally display a user-friendly message here
          }
      }, 100); // Delay initialization slightly (100ms)

      // Auto-hide alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);
    });
  </script>
</body>
</html>

<?php
$conn->close();
?> 