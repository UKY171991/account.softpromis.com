<?php
session_start();
include 'inc/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Income</title>
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
                <div class="col-12">
                    <h4 class="text-dark">Add Income</h4>
                </div>
            </div>

            <!-- Display Messages -->
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <?php if (isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success_msg']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_msg']); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Form to Add Income -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white d-flex align-items-center">
                            <i class="material-symbols-rounded me-2">attach_money</i>
                            <h6 class="mb-0">Add New Income</h6>
                        </div>
                        <div class="card-body">
                            <form action="process-income.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category_id" id="category" required>
                                        <option value="">-- Select Category --</option>
                                        <?php
                                        $query = "SELECT * FROM income_categories ORDER BY category_name ASC";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sub-Category</label>
                                    <select class="form-control" name="subcategory_id" id="subcategory" required>
                                        <option value="">-- Select Sub-Category --</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Actual Amount</label>
                                    <input type="number" class="form-control" name="actual_amount" id="actualAmount" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Received Amount</label>
                                    <input type="number" class="form-control" name="received_amount" id="receivedAmount" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Balance Amount</label>
                                    <input type="text" class="form-control" id="balanceAmount" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date of Entry</label>
                                    <input type="date" class="form-control" name="date_of_entry" required>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="material-symbols-rounded me-1">add</i> Add Income
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Table to Display Income -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Income Records</h6>
                        </div>
                        <div class="card-body px-3">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Sub-Category</th>
                                        <th>Amount</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT income.id, income.name, cat.category_name, sub.subcategory_name, income.actual_amount 
                                              FROM income 
                                              INNER JOIN income_categories cat ON income.category_id = cat.id 
                                              INNER JOIN income_subcategories sub ON income.subcategory_id = sub.id 
                                              ORDER BY income.id DESC";
                                    $result = mysqli_query($conn, $query);
                                    $count = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>{$count}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['category_name']}</td>
                                                <td>{$row['subcategory_name']}</td>
                                                <td>\${$row['actual_amount']}</td>
                                                <td class='text-center'>
                                                    <a href='edit-income.php?id={$row['id']}' class='badge badge-sm bg-gradient-success'><i class='fa fa-edit'></i> Edit</a>
                                                    <a href='delete-income.php?id={$row['id']}' class='badge badge-sm bg-gradient-danger' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i> Delete</a>
                                                </td>
                                              </tr>";
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

    <script>
        document.getElementById("category").addEventListener("change", function () {
            let categoryId = this.value;
            let subcategoryDropdown = document.getElementById("subcategory");
            subcategoryDropdown.innerHTML = "<option>Loading...</option>";

            fetch("fetch-subcategories.php?category_id=" + categoryId)
                .then(response => response.text())
                .then(data => {
                    subcategoryDropdown.innerHTML = data;
                });
        });

        document.getElementById("receivedAmount").addEventListener("input", function () {
            let actualAmount = parseFloat(document.getElementById("actualAmount").value) || 0;
            let receivedAmount = parseFloat(this.value) || 0;
            document.getElementById("balanceAmount").value = actualAmount - receivedAmount;
        });
    </script>

</body>
</html>
