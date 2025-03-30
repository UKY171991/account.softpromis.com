<?php
// [Your PHP code remains unchanged]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #344767;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <!-- Expenditure Section -->
            <div class="row">
                <div class="col-12">
                    <h6 class="section-title">Expenditure Overview</h6>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-money-bill-wave text-danger"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Total Expenditure</p>
                                <h4 class="mb-0">$<?= number_format($total_expenditure, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Updated Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Paid Expenditure</p>
                                <h4 class="mb-0">$<?= number_format($total_paid, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Amount Paid</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Balance Expenditure</p>
                                <h4 class="mb-0">$<?= number_format($total_balance, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Unpaid Amount</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-hourglass-half text-info"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Pending Payments</p>
                                <h4 class="mb-0">$<?= number_format($total_pending, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Amount Yet to be Cleared</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="section-title">Income Overview</h6>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-wallet text-success"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Total Income</p>
                                <h4 class="mb-0">$<?= number_format($total_income, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Updated Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-hand-holding-usd text-primary"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Received Income</p>
                                <h4 class="mb-0">$<?= number_format($received_income, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Amount Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-clock text-warning"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Balance Income</p>
                                <h4 class="mb-0">$<?= number_format($balance_income, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Pending Income</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-3">
                            <i class="fas fa-chart-line text-info"></i>
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Total Revenue</p>
                                <h4 class="mb-0">$<?= number_format($total_revenue, 2) ?></h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            <p class="mb-0 text-sm">Revenue from Operations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Expenditure Trends</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="expenditureChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
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
                                    backgroundColor: "rgba(255, 99, 132, 0.7)",
                                    data: data.expenditure,
                                    borderRadius: 5
                                },
                                {
                                    label: "Paid Amount",
                                    backgroundColor: "rgba(75, 192, 192, 0.7)",
                                    data: data.paid,
                                    borderRadius: 5
                                },
                                {
                                    label: "Balance Amount",
                                    backgroundColor: "rgba(255, 206, 86, 0.7)",
                                    data: data.balance,
                                    borderRadius: 5
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: "top" },
                                tooltip: { enabled: true }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
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
                                    backgroundColor: "rgba(75, 192, 192, 0.7)",
                                    data: data.income,
                                    borderRadius: 5
                                },
                                {
                                    label: "Received Amount",
                                    backgroundColor: "rgba(54, 162, 235, 0.7)",
                                    data: data.received,
                                    borderRadius: 5
                                },
                                {
                                    label: "Balance Amount",
                                    backgroundColor: "rgba(255, 206, 86, 0.7)",
                                    data: data.balance,
                                    borderRadius: 5
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: "top" },
                                tooltip: { enabled: true }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>