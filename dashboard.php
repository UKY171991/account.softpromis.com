<?php
// [Your PHP code remains unchanged]
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
            margin: 0;
        }
        .card-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #343a40;
            margin: 0.5rem 0 0;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 1.5rem;
        }
        .chart-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .chart-header {
            padding: 1rem 1.5rem;
            background: #343a40;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }
    </style>
</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-5">
            <!-- Expenditure Section -->
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Expenditure Overview</h2>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-arrow-up text-danger fa-lg"></i>
                            <div>
                                <p class="card-title">Total Expenditure</p>
                                <h3 class="card-value">$<?= number_format($total_expenditure, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Updated Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-check text-success fa-lg"></i>
                            <div>
                                <p class="card-title">Paid Expenditure</p>
                                <h3 class="card-value">$<?= number_format($total_paid, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Amount Paid</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-exclamation text-warning fa-lg"></i>
                            <div>
                                <p class="card-title">Balance Expenditure</p>
                                <h3 class="card-value">$<?= number_format($total_balance, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Unpaid Amount</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-hourglass text-info fa-lg"></i>
                            <div>
                                <p class="card-title">Pending Payments</p>
                                <h3 class="card-value">$<?= number_format($total_pending, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Yet to be Cleared</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Section -->
            <div class="row mt-3">
                <div class="col-12">
                    <h2 class="section-title">Income Overview</h2>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-arrow-down text-success fa-lg"></i>
                            <div>
                                <p class="card-title">Total Income</p>
                                <h3 class="card-value">$<?= number_format($total_income, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Updated Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-hand-holding-usd text-primary fa-lg"></i>
                            <div>
                                <p class="card-title">Received Income</p>
                                <h3 class="card-value">$<?= number_format($received_income, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Amount Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                            <div>
                                <p class="card-title">Balance Income</p>
                                <h3 class="card-value">$<?= number_format($balance_income, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">Pending Income</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-line text-info fa-lg"></i>
                            <div>
                                <p class="card-title">Total Revenue</p>
                                <h3 class="card-value">$<?= number_format($total_revenue, 2) ?></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-sm text-muted">From Operations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h6 class="mb-0">Expenditure Trends</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="expenditureChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <div class="chart-header">
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
                                    backgroundColor: "rgba(220, 53, 69, 0.8)",
                                    data: data.expenditure,
                                    borderRadius: 8,
                                    borderWidth: 0
                                },
                                {
                                    label: "Paid Amount",
                                    backgroundColor: "rgba(40, 167, 69, 0.8)",
                                    data: data.paid,
                                    borderRadius: 8,
                                    borderWidth: 0
                                },
                                {
                                    label: "Balance Amount",
                                    backgroundColor: "rgba(255, 193, 7, 0.8)",
                                    data: data.balance,
                                    borderRadius: 8,
                                    borderWidth: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: { font: { size: 12, family: "Inter" } }
                                },
                                tooltip: { backgroundColor: "#343a40" }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: "rgba(0, 0, 0, 0.05)" }
                                },
                                x: {
                                    grid: { display: false }
                                }
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
                                    backgroundColor: "rgba(40, 167, 69, 0.8)",
                                    data: data.income,
                                    borderRadius: 8,
                                    borderWidth: 0
                                },
                                {
                                    label: "Received Amount",
                                    backgroundColor: "rgba(0, 123, 255, 0.8)",
                                    data: data.received,
                                    borderRadius: 8,
                                    borderWidth: 0
                                },
                                {
                                    label: "Balance Amount",
                                    backgroundColor: "rgba(255, 193, 7, 0.8)",
                                    data: data.balance,
                                    borderRadius: 8,
                                    borderWidth: 0
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: { font: { size: 12, family: "Inter" } }
                                },
                                tooltip: { backgroundColor: "#343a40" }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: "rgba(0, 0, 0, 0.05)" }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>