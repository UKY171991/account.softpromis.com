<?php
include 'inc/auth.php';
include 'inc/config.php';

// Fetch filter inputs safely
$person_name = isset($_POST['person_name']) ? trim($_POST['person_name']) : '';
$category = isset($_POST['category']) ? intval($_POST['category']) : 0;
$subcategory = isset($_POST['subcategory']) ? intval($_POST['subcategory']) : 0;
$month = isset($_POST['month']) ? trim($_POST['month']) : '';
$year = isset($_POST['year']) ? intval($_POST['year']) : 0;
$pending = isset($_POST['pending']) ? intval($_POST['pending']) : '';

// Construct query dynamically
$query = "SELECT t.id, t.person_name, t.category_id, t.subcategory_id, t.total_amount, 
                 t.paid_amount, t.pending_amount, t.transaction_date, 
                 c.category_name, s.subcategory_name
          FROM transactions t
          LEFT JOIN expenditure_categories c ON t.category_id = c.id
          LEFT JOIN expenditure_subcategories s ON t.subcategory_id = s.id
          WHERE 1=1";  

$params = [];
$types = "";

// Append filters dynamically
if (!empty($person_name)) {
    $query .= " AND t.person_name LIKE ?";
    $params[] = "%$person_name%";
    $types .= "s";
}

if (!empty($category)) {
    $query .= " AND t.category_id = ?";
    $params[] = $category;
    $types .= "i";
}

if (!empty($subcategory)) {
    $query .= " AND t.subcategory_id = ?";
    $params[] = $subcategory;
    $types .= "i";
}

if (!empty($month)) {
    $query .= " AND DATE_FORMAT(t.transaction_date, '%Y-%m') = ?";
    $params[] = $month;
    $types .= "s";
}

if (!empty($year)) {
    $query .= " AND YEAR(t.transaction_date) = ?";
    $params[] = $year;
    $types .= "i";
}

if ($pending !== '') {
    $query .= " AND t.pending_amount " . ($pending == 1 ? "> 0" : "= 0");
}

// Order results
$query .= " ORDER BY t.transaction_date DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generated Report</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-dark">Generated Report</h4>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="mb-0">Report Details</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($result->num_rows > 0): ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Person</th>
                                            <th>Category</th>
                                            <th>Sub-category</th>
                                            <th>Total Amount</th>
                                            <th>Paid</th>
                                            <th>Pending</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id']); ?></td>
                                                <td><?= htmlspecialchars($row['person_name']); ?></td>
                                                <td><?= htmlspecialchars($row['category_name'] ?? 'N/A'); ?></td>
                                                <td><?= htmlspecialchars($row['subcategory_name'] ?? 'N/A'); ?></td>
                                                <td><?= number_format($row['total_amount'], 2); ?></td>
                                                <td><?= number_format($row['paid_amount'], 2); ?></td>
                                                <td><?= number_format($row['pending_amount'], 2); ?></td>
                                                <td><?= date("d-m-Y", strtotime($row['transaction_date'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="alert alert-warning">No records found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button onclick="window.print()" class="btn bg-gradient-dark">Print Report</button>
                <a href="reports.php" class="btn btn-secondary">Back to Reports</a>
            </div>
        </div>
    </main>

    <script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>
