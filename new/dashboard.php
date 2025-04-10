<?php
include 'inc/auth.php'; // Include the authentication file to check user login status
// Database connection
include 'inc/config.php';

// Fetch total income
$totalIncomeQuery = "SELECT SUM(amount) AS total_income FROM income";
$totalIncomeResult = $conn->query($totalIncomeQuery);
$totalIncome = $totalIncomeResult->fetch_assoc()['total_income'] ?? 0;

// Fetch total expenditure
$totalExpenditureQuery = "SELECT SUM(amount) AS total_expenditure FROM expenditures";
$totalExpenditureResult = $conn->query($totalExpenditureQuery);
$totalExpenditure = $totalExpenditureResult->fetch_assoc()['total_expenditure'] ?? 0;

// Fetch pending payments
$pendingPaymentsQuery = "SELECT SUM(balance) AS pending_payments FROM expenditures";
$pendingPaymentsResult = $conn->query($pendingPaymentsQuery);
$pendingPayments = $pendingPaymentsResult->fetch_assoc()['pending_payments'] ?? 0;

// Fetch current year's total income
$currentYearIncomeQuery = "SELECT SUM(amount) AS total_income FROM income WHERE YEAR(date) = YEAR(CURDATE())";
$currentYearIncomeResult = $conn->query($currentYearIncomeQuery);
$currentYearIncome = $currentYearIncomeResult->fetch_assoc()['total_income'] ?? 0;

// Fetch current year's total expenditure
$currentYearExpenditureQuery = "SELECT SUM(amount) AS total_expenditure FROM expenditures WHERE YEAR(date) = YEAR(CURDATE())";
$currentYearExpenditureResult = $conn->query($currentYearExpenditureQuery);
$currentYearExpenditure = $currentYearExpenditureResult->fetch_assoc()['total_expenditure'] ?? 0;

// Fetch current year's pending payments
$currentYearPendingPaymentsQuery = "SELECT SUM(balance) AS pending_payments FROM expenditures WHERE YEAR(date) = YEAR(CURDATE())";
$currentYearPendingPaymentsResult = $conn->query($currentYearPendingPaymentsQuery);
$currentYearPendingPayments = $currentYearPendingPaymentsResult->fetch_assoc()['pending_payments'] ?? 0;

// Fetch monthly income and expenditure
$monthlyIncomeQuery = "SELECT MONTH(date) AS month, SUM(amount) AS total FROM income GROUP BY MONTH(date)";
$monthlyIncomeResult = $conn->query($monthlyIncomeQuery);
$monthlyIncomeData = [];
while ($row = $monthlyIncomeResult->fetch_assoc()) {
    $monthlyIncomeData[$row['month']] = $row['total'];
}

$monthlyExpenditureQuery = "SELECT MONTH(date) AS month, SUM(amount) AS total FROM expenditures GROUP BY MONTH(date)";
$monthlyExpenditureResult = $conn->query($monthlyExpenditureQuery);
$monthlyExpenditureData = [];
while ($row = $monthlyExpenditureResult->fetch_assoc()) {
    $monthlyExpenditureData[$row['month']] = $row['total'];
}

// Fetch income distribution
$incomeDistributionQuery = "SELECT category, SUM(amount) AS total FROM income GROUP BY category";
$incomeDistributionResult = $conn->query($incomeDistributionQuery);
$incomeDistributionData = [];
while ($row = $incomeDistributionResult->fetch_assoc()) {
    $incomeDistributionData[] = ['category' => $row['category'], 'total' => $row['total']];
}

// Fetch expenditure distribution
$expenditureDistributionQuery = "SELECT category, SUM(amount) AS total FROM expenditures GROUP BY category";
$expenditureDistributionResult = $conn->query($expenditureDistributionQuery);
$expenditureDistributionData = [];
while ($row = $expenditureDistributionResult->fetch_assoc()) {
    $expenditureDistributionData[] = ['category' => $row['category'], 'total' => $row['total']];
}
?>

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
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .top-navbar {
      position: sticky;
      top: 0;
      z-index: 1030;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    canvas {
      background-color: white;
      border-radius: 1rem;
      padding: 1rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="add-income.php" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Add Income</a>
            <a href="add-expenditure.php" class="btn btn-sm btn-danger"><i class="bi bi-dash-circle"></i> Add Expenditure</a>
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
              <h3 class="text-success">₹<?php echo number_format($totalIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Expenditure</h5>
              <h3 class="text-danger">₹<?php echo number_format($totalExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Pending Payments</h5>
              <h3 class="text-warning">₹<?php echo number_format($pendingPayments, 2); ?></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Income (This Year)</h5>
              <h3 class="text-success">₹<?php echo number_format($currentYearIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Expenditure (This Year)</h5>
              <h3 class="text-danger">₹<?php echo number_format($currentYearExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending Payments (This Year)</h5>
              <h3 class="text-warning">₹<?php echo number_format($currentYearPendingPayments, 2); ?></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Income (This Month)</h5>
              <h3 class="text-success">₹<?php echo number_format($monthlyIncomeData[date('n')] ?? 0, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Expenditure (This Month)</h5>
              <h3 class="text-danger">₹<?php echo number_format($monthlyExpenditureData[date('n')] ?? 0, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending (This Month)</h5>
              <h3 class="text-warning">₹<?php echo number_format(($monthlyIncomeData[date('n')] ?? 0) - ($monthlyExpenditureData[date('n')] ?? 0), 2); ?></h3>
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

      <div class="row g-4 mt-4">
        <div class="col-md-6">
          <h5 class="mb-3">Income Distribution</h5>
          <canvas id="incomePieChart" height="200" width="200"></canvas>
        </div>
        <div class="col-md-6">
          <h5 class="mb-3">Expenditure Distribution</h5>
          <canvas id="expenditurePieChart" height="200" width="200"></canvas>
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
        labels: <?php echo json_encode(array_keys($monthlyIncomeData)); ?>,
        datasets: [{
          label: 'Income',
          data: <?php echo json_encode(array_values($monthlyIncomeData)); ?>,
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
        labels: <?php echo json_encode(array_keys($monthlyExpenditureData)); ?>,
        datasets: [{
          label: 'Expenditure',
          data: <?php echo json_encode(array_values($monthlyExpenditureData)); ?>,
          backgroundColor: 'rgba(220,53,69,0.7)'
        }]
      }
    });

    // Income Pie Chart
    const incomePieChart = new Chart(document.getElementById('incomePieChart'), {
      type: 'pie',
      data: {
        labels: <?php echo json_encode(array_column($incomeDistributionData, 'category')); ?>,
        datasets: [{
          data: <?php echo json_encode(array_column($incomeDistributionData, 'total')); ?>,
          backgroundColor: ['#4caf50', '#2196f3', '#ff9800'],
          borderColor: '#ffffff',
          borderWidth: 2,
          hoverOffset: 6
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: {
                size: 10
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                const value = tooltipItem.raw;
                const percentage = ((value / total) * 100).toFixed(2);
                return `${tooltipItem.label}: ₹${value} (${percentage}%)`;
              }
            },
            bodyFont: {
              size: 10
            }
          }
        }
      }
    });

    // Expenditure Pie Chart
    const expenditurePieChart = new Chart(document.getElementById('expenditurePieChart'), {
      type: 'pie',
      data: {
        labels: <?php echo json_encode(array_column($expenditureDistributionData, 'category')); ?>,
        datasets: [{
          data: <?php echo json_encode(array_column($expenditureDistributionData, 'total')); ?>,
          backgroundColor: ['#e91e63', '#ff5722', '#9c27b0'],
          borderColor: '#ffffff',
          borderWidth: 2,
          hoverOffset: 6
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: {
                size: 10
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                const value = tooltipItem.raw;
                const percentage = ((value / total) * 100).toFixed(2);
                return `${tooltipItem.label}: ₹${value} (${percentage}%)`;
              }
            },
            bodyFont: {
              size: 10
            }
          }
        }
      }
    });
  </script>
</body>

</html>
