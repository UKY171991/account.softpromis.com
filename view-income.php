<?php
session_start();
include 'inc/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Income</title>
    <link rel="stylesheet" href="assets/css/material-dashboard.css?v=3.2.0">
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark">Income Records</h4>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-gradient-dark text-white">
                    <h6 class="mb-0">All Income Entries</h6>
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
                            $query = "SELECT * FROM income ORDER BY id DESC";
                            $result = mysqli_query($conn, $query);
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['category_id']}</td>
                                        <td>{$row['subcategory_id']}</td>
                                        <td>\${$row['actual_amount']}</td>
                                        <td class='text-center'>
                                            <a href='edit-income.php?id={$row['id']}' class='badge bg-gradient-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='delete-income.php?id={$row['id']}' class='badge bg-gradient-danger' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-trash'></i> Delete</a>
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
    </main>
</body>
</html>
