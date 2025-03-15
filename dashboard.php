<?php
// Include authentication and configuration files
include 'inc/auth.php';
include 'inc/config.php';

// Fetch real-time data
$todayDate = date('Y-m-d');

// Total Income
$incomeQuery = "SELECT SUM(actual_amount) AS total_income FROM expenditure";
$incomeResult = $conn->query($incomeQuery);
$income = $incomeResult->fetch_assoc()['total_income'] ?? 0;

// Total Expenditure
$expenseQuery = "SELECT SUM(paid_amount) AS paid_amount FROM expenditure";
$expenseResult = $conn->query($expenseQuery);
$paid_amount = $expenseResult->fetch_assoc()['paid_amount'] ?? 0;


// Total Users
$userQuery = "SELECT COUNT(id) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$users = $userResult->fetch_assoc()['total_users'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="ms-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
                    <p class="mb-4">Overview of your system statistics.</p>
                </div>

                <!-- Income Card -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <p class="text-sm mb-0 text-capitalize">Total Income</p>
                            <h4 class="mb-0">$<?= number_format($income, 2) ?></h4>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Updated Today</p>
                        </div>
                    </div>
                </div>

                <!-- Expenditure Card -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <p class="text-sm mb-0 text-capitalize">Total Expenditure</p>
                            <h4 class="mb-0">$<?= number_format($paid_amount, 2) ?></h4>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Updated Today</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments Card -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <p class="text-sm mb-0 text-capitalize">Pending Payments</p>
                            <h4 class="mb-0">$<?= number_format($income - $paid_amount, 2) ?></h4>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Pending transactions</p>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <p class="text-sm mb-0 text-capitalize">Total Users</p>
                            <h4 class="mb-0"><?= $users ?></h4>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm">Registered users</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income vs Expenditure Chart -->
            <div class="row mt-4">
              <div class="col-md-12">
                  <div class="card shadow-lg">
                      <div class="card-header bg-gradient-dark text-white">
                          <h6 class="mb-0">Expenditure Trends</h6>
                      </div>
                      <div class="card-body">
                          <canvas id="expenditureChart"></canvas>
                      </div>
                  </div>
              </div>
          </div>


            <div class="row mt-4">
              <div class="col-md-12">
                  <div class="card shadow-lg">
                      <div class="card-header bg-gradient-dark text-white">
                          <h6 class="mb-0">Income Trends</h6>
                      </div>
                      <div class="card-body">
                          <canvas id="incomeChart"></canvas>
                      </div>
                  </div>
              </div>
          </div>


        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      // fetch-dashboard-data.php
        document.addEventListener("DOMContentLoaded", function () {
          fetch("fetch-dashboard-data.php")
              .then(response => response.json())
              .then(data => {
                  const ctx = document.getElementById("expenditureChart").getContext("2d");
                  new Chart(ctx, {
                      type: "bar",
                      data: {
                          labels: data.labels,
                          datasets: [
                              {
                                  label: "Total Expenditure",
                                  backgroundColor: "red",
                                  data: data.expenditure
                              },
                              {
                                  label: "Paid Amount",
                                  backgroundColor: "green",
                                  data: data.paid
                              },
                              {
                                  label: "Balance Amount",
                                  backgroundColor: "orange",
                                  data: data.balance
                              }
                          ]
                      }
                  });
              });
      });





    document.addEventListener("DOMContentLoaded", function () {
        fetch("fetch-income-dashboard.php")
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById("incomeChart").getContext("2d");
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: "Total Income",
                                backgroundColor: "green",
                                data: data.income
                            },
                            {
                                label: "Received Amount",
                                backgroundColor: "blue",
                                data: data.received
                            },
                            {
                                label: "Balance Amount",
                                backgroundColor: "orange",
                                data: data.balance
                            }
                        ]
                    }
                });
            });
    });

    </script>

</body>
</html>
