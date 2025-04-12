<?php
include 'inc/auth.php'; // Include the authentication file to check user login status
// Database connection
include 'inc/config.php';

// Check if user is manager and redirect if true
if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'manager') {
    // Redirect to income page with error message since managers can't access dashboard
    header("Location: income.php?error=You do not have permission to access the dashboard");
    exit();
}

// Generate financial years (last 5 years including the current financial year)
$currentYear = date('Y');
$currentMonth = date('n');
$startYear = ($currentMonth >= 4) ? $currentYear : $currentYear - 1; // Financial year starts in April
$financialYears = [];
for ($i = 0; $i < 5; $i++) {
    $endYear = $startYear + 1;
    $financialYears[] = "$startYear-$endYear";
    $startYear--;
}

// Determine the default financial year
$defaultFinancialYear = ($currentMonth >= 4) ? date('Y') . '-' . (date('Y') + 1) : (date('Y') - 1) . '-' . date('Y');

// Get the selected financial year from the query parameter or use the default
$selectedFinancialYear = $_GET['financial_year'] ?? $defaultFinancialYear;
list($startYear, $endYear) = explode('-', $selectedFinancialYear);

// Update SQL queries to filter data based on the selected financial year

// Fetch total income for the selected financial year
$totalIncomeQuery = "
  SELECT SUM(amount) AS total_income 
  FROM income 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)";
$totalIncomeResult = $conn->query($totalIncomeQuery);
$totalIncome = $totalIncomeResult->fetch_assoc()['total_income'] ?? 0;

// Fetch total expenditure for the selected financial year
$totalExpenditureQuery = "
  SELECT SUM(amount) AS total_expenditure 
  FROM expenditures 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)";
$totalExpenditureResult = $conn->query($totalExpenditureQuery);
$totalExpenditure = $totalExpenditureResult->fetch_assoc()['total_expenditure'] ?? 0;

// Fetch pending payments for the selected financial year
$pendingPaymentsQuery = "
  SELECT SUM(balance) AS pending_payments 
  FROM expenditures 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)";
$pendingPaymentsResult = $conn->query($pendingPaymentsQuery);
$pendingPayments = $pendingPaymentsResult->fetch_assoc()['pending_payments'] ?? 0;

// Fetch pending income for the current month
$currentMonthPendingIncomeQuery = "
  SELECT SUM(balance) AS pending_income 
  FROM income 
  WHERE MONTH(date) = $currentMonth AND YEAR(date) = $currentYear";
$currentMonthPendingIncomeResult = $conn->query($currentMonthPendingIncomeQuery);
$currentMonthPendingIncome = $currentMonthPendingIncomeResult->fetch_assoc()['pending_income'] ?? 0;

// Fetch pending expenditure for the current month
$currentMonthPendingExpenditureQuery = "
  SELECT SUM(balance) AS pending_expenditure 
  FROM expenditures 
  WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
$currentMonthPendingExpenditureResult = $conn->query($currentMonthPendingExpenditureQuery);
$currentMonthPendingExpenditure = $currentMonthPendingExpenditureResult->fetch_assoc()['pending_expenditure'] ?? 0;

// Fetch pending income for the current financial year
$currentYearPendingIncomeQuery = "
  SELECT SUM(balance) AS pending_income 
  FROM income 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)";
$currentYearPendingIncomeResult = $conn->query($currentYearPendingIncomeQuery);
$currentYearPendingIncome = $currentYearPendingIncomeResult->fetch_assoc()['pending_income'] ?? 0;

// Fetch pending expenditure for the current financial year
$currentYearPendingExpenditureQuery = "
  SELECT SUM(balance) AS pending_expenditure 
  FROM expenditures 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = YEAR(CURRENT_DATE())) OR 
    (MONTH(date) < 4 AND YEAR(date) = YEAR(CURRENT_DATE()) + 1)";
$currentYearPendingExpenditureResult = $conn->query($currentYearPendingExpenditureQuery);
$currentYearPendingExpenditure = $currentYearPendingExpenditureResult->fetch_assoc()['pending_expenditure'] ?? 0;

// Fetch monthly income for the selected financial year
$monthlyIncomeQuery = "
  SELECT 
    MONTH(date) AS month, 
    SUM(amount) AS total 
  FROM income 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)
  GROUP BY MONTH(date)";
$monthlyIncomeResult = $conn->query($monthlyIncomeQuery);
$monthlyIncomeData = [];
while ($row = $monthlyIncomeResult->fetch_assoc()) {
    $monthlyIncomeData[$row['month']] = $row['total'];
}

// Fetch monthly expenditure for the selected financial year
$monthlyExpenditureQuery = "
  SELECT 
    MONTH(date) AS month, 
    SUM(amount) AS total 
  FROM expenditures 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)
  GROUP BY MONTH(date)";
$monthlyExpenditureResult = $conn->query($monthlyExpenditureQuery);
$monthlyExpenditureData = [];
while ($row = $monthlyExpenditureResult->fetch_assoc()) {
    $monthlyExpenditureData[$row['month']] = $row['total'];
}

// Generate labels for the financial year (April to March)
$financialYearLabels = [];
for ($i = 4; $i <= 12; $i++) {
    $financialYearLabels[] = date('F', mktime(0, 0, 0, $i, 1));
}
for ($i = 1; $i <= 3; $i++) {
    $financialYearLabels[] = date('F', mktime(0, 0, 0, $i, 1));
}

// Fetch income distribution for the selected financial year
$incomeDistributionQuery = "
  SELECT category, SUM(amount) AS total 
  FROM income 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)
  GROUP BY category";
$incomeDistributionResult = $conn->query($incomeDistributionQuery);
$incomeDistributionData = [];
while ($row = $incomeDistributionResult->fetch_assoc()) {
    $incomeDistributionData[] = ['category' => $row['category'], 'total' => $row['total']];
}

// Fetch expenditure distribution for the selected financial year
$expenditureDistributionQuery = "
  SELECT category, SUM(amount) AS total 
  FROM expenditures 
  WHERE 
    (MONTH(date) >= 4 AND YEAR(date) = $startYear) OR 
    (MONTH(date) < 4 AND YEAR(date) = $endYear)
  GROUP BY category";
$expenditureDistributionResult = $conn->query($expenditureDistributionQuery);
$expenditureDistributionData = [];
while ($row = $expenditureDistributionResult->fetch_assoc()) {
    $expenditureDistributionData[] = ['category' => $row['category'], 'total' => $row['total']];
}

// Prepare data for Income vs Expenditure Distribution
$categories = [];
$incomeDistribution = [];
$expenditureDistribution = [];

// Combine income and expenditure categories
foreach ($incomeDistributionData as $income) {
    $categories[$income['category']] = true;
    $incomeDistribution[$income['category']] = $income['total'];
}
foreach ($expenditureDistributionData as $expenditure) {
    $categories[$expenditure['category']] = true;
    $expenditureDistribution[$expenditure['category']] = $expenditure['total'];
}

// Ensure all categories are present in both datasets
$categories = array_keys($categories);
$incomeDataForChart = [];
$expenditureDataForChart = [];
foreach ($categories as $category) {
    $incomeDataForChart[] = $incomeDistribution[$category] ?? 0;
    $expenditureDataForChart[] = $expenditureDistribution[$category] ?? 0;
}

// Calculate total income and total expenditure for the pie chart
$totalIncomeForPie = array_sum(array_column($incomeDistributionData, 'total'));
$totalExpenditureForPie = array_sum(array_column($expenditureDistributionData, 'total'));

// Prepare data for the pie chart
$distributionPieData = [
    'Income' => $totalIncomeForPie,
    'Expenditure' => $totalExpenditureForPie
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/responsive.css">
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
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content w-100">
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($_GET['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <h2>Dashboard</h2>
      <!-- Top Navbar -->
      <nav class="navbar top-navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1">Dashboard</span>

          <div class="d-flex align-items-center gap-2">
            <a href="add-income.php" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Add Income</a>
            <a href="add-expenditure.php" class="btn btn-sm btn-danger"><i class="bi bi-dash-circle"></i> Add Expenditure</a>
          </div>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="btn btn-secondary dropdown-toggle" type="button" id="financialYearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <?php echo $selectedFinancialYear; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="financialYearDropdown">
                <?php foreach ($financialYears as $year): ?>
                  <li>
                    <a class="dropdown-item financial-year-option" href="#" data-year="<?php echo $year; ?>">
                      <?php echo $year; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </li>

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
       <!----
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Income</h5>
              <h3 class="text-success">₹<?php // echo number_format($totalIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Expenditure</h5>
              <h3 class="text-danger">₹<?php // echo number_format($totalExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Total Pending Payments</h5>
              <h3 class="text-warning">₹<?php // echo number_format($pendingPayments, 2); ?></h3>
            </div>
          </div>
        </div>
      </div>
      --->

      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Income (This Year)</h5>
              <h3 class="text-success">₹<?php echo number_format($totalIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Income (This Month)</h5>
              <h3 class="text-success">₹<?php echo number_format($monthlyIncomeData[date('n')] ?? 0, 2); ?></h3>
            </div>
          </div>
        </div>
        <!-- Pending Income (Current Month) -->
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending (This Month)</h5>
              <h3 class="text-warning">₹<?php echo number_format($currentMonthPendingIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        <!-- Pending Income (Current Year) -->
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending (This Year)</h5>
              <h3 class="text-warning">₹<?php echo number_format($currentYearPendingIncome, 2); ?></h3>
            </div>
          </div>
        </div>
        </div>

      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Expenditure (This Year)</h5>
              <h3 class="text-success">₹<?php echo number_format($totalExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Expenditure (This Month)</h5>
              <h3 class="text-success">₹<?php echo number_format($monthlyExpenditureData[date('n')] ?? 0, 2); ?></h3>
            </div>
          </div>
        </div>
        
      
      

        <!-- Pending Expenditure (Current Month) -->
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending Expenditure (This Month)</h5>
              <h3 class="text-warning">₹<?php echo number_format($currentMonthPendingExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>

        

        <!-- Pending Expenditure (Current Year) -->
        <div class="col-md-3">
          <div class="card dashboard-card p-3">
            <div class="card-body">
              <h5 class="card-title">Pending Expenditure (This Year)</h5>
              <h3 class="text-warning">₹<?php echo number_format($currentYearPendingExpenditure, 2); ?></h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Graphs Section -->
      <div class="row g-4">
        <div class="col-md-4">
          <h5 class="mb-3">Monthly Income Trend</h5>
          <canvas id="incomeChart" height="300"></canvas>
        </div>

        <div class="col-md-4">
          <h5 class="mb-3">Income vs Expenditure (Line Chart)</h5>
          <canvas id="combinedChart" height="300"></canvas>
        </div>
        
        <div class="col-md-4">
          <h5 class="mb-3">Monthly Expenditure Trend</h5>
          <canvas id="expenditureChart" height="300"></canvas>
        </div>
      </div>

      <div class="row g-4 mt-4">
        <div class="col-md-4">
          <h5 class="mb-3">Income Distribution</h5>
          <canvas id="incomePieChart" height="200" width="200"></canvas>
        </div>

        <div class="col-md-4">
          <h5 class="mb-3 text-center">Income vs Expenditure</h5>
          <canvas id="distributionPieChart" height="300"></canvas>
        </div>

        <div class="col-md-4">
          <h5 class="mb-3">Expenditure Distribution</h5>
          <canvas id="expenditurePieChart" height="200" width="200"></canvas>
        </div>
        
      </div>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="assets/js/responsive.js"></script>
  <script>
    // Monthly Income Trend
    const incomeChart = new Chart(document.getElementById('incomeChart'), {
      type: 'line',
      data: {
        labels: <?php echo json_encode($financialYearLabels); ?>,
        datasets: [{
          label: 'Income',
          data: <?php
            $incomeDataForGraph = [];
            for ($i = 4; $i <= 12; $i++) {
                $incomeDataForGraph[] = $monthlyIncomeData[$i] ?? 0;
            }
            for ($i = 1; $i <= 3; $i++) {
                $incomeDataForGraph[] = $monthlyIncomeData[$i] ?? 0;
            }
            echo json_encode($incomeDataForGraph);
          ?>,
          borderColor: 'green',
          backgroundColor: 'rgba(0, 128, 0, 0.1)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Income (₹)'
            }
          }
        }
      }
    });

    // Monthly Expenditure Trend
    const expenditureChart = new Chart(document.getElementById('expenditureChart'), {
      type: 'line',
      data: {
        labels: <?php echo json_encode($financialYearLabels); ?>, // Financial year labels
        datasets: [{
          label: 'Expenditure',
          data: <?php
            // Map expenditure data to financial year order
            $expenditureDataForGraph = [];
            for ($i = 4; $i <= 12; $i++) {
                $expenditureDataForGraph[] = $monthlyExpenditureData[$i] ?? 0;
            }
            for ($i = 1; $i <= 3; $i++) {
                $expenditureDataForGraph[] = $monthlyExpenditureData[$i] ?? 0;
            }
            echo json_encode($expenditureDataForGraph);
          ?>,
          borderColor: 'red',
          backgroundColor: 'rgba(255, 0, 0, 0.1)',
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Expenditure (₹)'
            }
          }
        }
      }
    });

    // Income Pie Chart
    const incomePieChart = new Chart(document.getElementById('incomePieChart'), {
      type: 'pie',
      data: {
        labels: <?php echo json_encode(array_column($incomeDistributionData, 'category')); ?>,
        datasets: [{
          data: <?php echo json_encode(array_column($incomeDistributionData, 'total')); ?>,
          backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#e91e63'],
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
          backgroundColor: ['#e91e63', '#ff5722', '#9c27b0', '#03a9f4', '#8bc34a'],
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
            }
          }
        }
      }
    });

    // Combined Income vs Expenditure Graph
    const combinedChart = new Chart(document.getElementById('combinedChart'), {
      type: 'line',
      data: {
        labels: <?php echo json_encode($financialYearLabels); ?>,
        datasets: [
          {
            label: 'Income',
            data: <?php echo json_encode($incomeDataForGraph); ?>,
            borderColor: 'green',
            backgroundColor: 'rgba(0, 128, 0, 0.1)',
            tension: 0.3,
            fill: true
          },
          {
            label: 'Expenditure',
            data: <?php echo json_encode($expenditureDataForGraph); ?>,
            borderColor: 'red',
            backgroundColor: 'rgba(255, 0, 0, 0.1)',
            tension: 0.3,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Amount (₹)'
            }
          }
        }
      }
    });

    // Income Distribution vs Expenditure Distribution Chart
    const distributionComparisonChart = new Chart(document.getElementById('distributionComparisonChart'), {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($categories); ?>, // Categories
        datasets: [
          {
            label: 'Income',
            data: <?php echo json_encode($incomeDataForChart); ?>,
            backgroundColor: 'rgba(0, 128, 0, 0.7)', // Green for income
            borderColor: 'rgba(0, 128, 0, 1)',
            borderWidth: 1
          },
          {
            label: 'Expenditure',
            data: <?php echo json_encode($expenditureDataForChart); ?>,
            backgroundColor: 'rgba(255, 0, 0, 0.7)', // Red for expenditure
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Categories'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Amount (₹)'
            },
            beginAtZero: true
          }
        }
      }
    });

    // Income Distribution vs Expenditure Distribution Pie Chart
    const distributionPieChart = new Chart(document.getElementById('distributionPieChart'), {
      type: 'pie',
      data: {
        labels: <?php echo json_encode(array_keys($distributionPieData)); ?>, // Labels: Income, Expenditure
        datasets: [{
          data: <?php echo json_encode(array_values($distributionPieData)); ?>, // Data: Total income and expenditure
          backgroundColor: ['#4caf50', '#f44336'], // Green for income, red for expenditure
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
                size: 12
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
            }
          }
        }
      }
    });

    // Financial Year Selection
    document.querySelectorAll('.financial-year-option').forEach(item => {
      item.addEventListener('click', function (e) {
        e.preventDefault();
        const selectedYear = this.getAttribute('data-year');
        // Reload the page with the selected financial year as a query parameter
        window.location.href = `dashboard.php?financial_year=${selectedYear}`;
      });
    });
  </script>
</body>

</html>
