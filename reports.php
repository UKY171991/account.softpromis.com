<?php
include 'inc/auth.php';
include 'inc/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-dark">Generate Reports</h4>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Filter Reports</h6>
                        </div>
                        <div class="card-body">
                            <form action="generate-report.php" method="POST" target="_blank">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Filter by Person</label>
                                            <input type="text" class="form-control border" name="person_name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-control border" name="category" id="categorySelect">
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                $result = $conn->query("SELECT * FROM expenditure_categories ORDER BY category_name ASC");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Sub-category</label>
                                            <select class="form-control border" name="subcategory" id="subcategorySelect">
                                                <option value="">-- Select Sub-category --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Month</label>
                                            <input type="month" class="form-control border" name="month">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Year</label>
                                            <input type="number" class="form-control border" name="year" min="2000" max="<?= date('Y'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pending Payments</label>
                                            <select class="form-control border" name="pending">
                                                <option value="">-- Select --</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" name="generate" class="btn bg-gradient-dark">Generate Report</button>
                                    <button type="submit" name="export_pdf" class="btn btn-danger">Export as PDF</button>
                                    <button type="submit" name="export_excel" class="btn btn-success">Export as Excel</button>
                                    <button type="submit" name="export_csv" class="btn btn-info">Export as CSV</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart for Income vs Expenditure -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Income vs. Expenditure</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="incomeExpenditureChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

    <script>
        fetch("fetch-report-data.php")
	    .then(response => response.json())
	    .then(data => {
	        const ctx = document.getElementById("incomeExpenditureChart").getContext("2d");
	        new Chart(ctx, {
	            type: "bar",
	            data: {
	                labels: data.labels,
	                datasets: [
	                    {
	                        label: "Income",
	                        backgroundColor: "green",
	                        data: data.income
	                    },
	                    {
	                        label: "Expenditure",
	                        backgroundColor: "red",
	                        data: data.expenditure
	                    }
	                ]
	            }
	        });
	    });

    </script>

    <script>
    	
    $(document).ready(function() {

            // Fetch subcategories when category is selected
            $("#categorySelect").change(function() {
                let categoryId = $(this).val();
                $("#subcategorySelect").html('<option value="">Loading...</option>');

                if (categoryId !== "") {
                    $.ajax({
                        url: "fetch-expenditure-subcategories.php",
                        type: "POST",
                        data: { category_id: categoryId },
                        success: function(response) {
                            $("#subcategorySelect").html(response);
                        },
                        error: function() {
                            $("#subcategorySelect").html('<option value="">Error loading subcategories</option>');
                        }
                    });
                } else {
                    $("#subcategorySelect").html('<option value="">-- Select Sub-Category --</option>');
                }
            });
        });
        
    </script>

</body>
</html>
