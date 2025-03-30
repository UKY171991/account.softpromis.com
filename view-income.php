<?php
include 'inc/auth.php';
include 'inc/config.php';

// Create income_data.php for AJAX separately
if(isset($_GET['ajax'])) {
    $query = "SELECT 
                i.id, i.name, i.phone, i.description, 
                c.category_name, s.subcategory_name, 
                i.actual_amount, i.received_amount, 
                i.balance_amount, i.revenue, i.entry_date 
              FROM income i
              INNER JOIN income_categories c ON i.category_id = c.id
              INNER JOIN income_subcategories s ON i.subcategory_id = s.id
              ORDER BY i.id DESC";
              
    $result = mysqli_query($conn, $query);
    $data = [];
    $count = 1;

    while($row = mysqli_fetch_assoc($result)) {
        $actions = '<a href="edit-income.php?id='.$row['id'].'" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a> '
                 .'<a href="delete-income.php?id='.$row['id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this entry?\')"><i class="fa fa-trash"></i></a>';

        $data[] = [
            $count++,
            htmlspecialchars($row['name']),
            htmlspecialchars($row['phone']),
            htmlspecialchars($row['category_name']),
            htmlspecialchars($row['subcategory_name']),
            number_format($row['actual_amount'], 2),
            number_format($row['received_amount'], 2),
            number_format($row['balance_amount'], 2),
            number_format($row['revenue'], 2),
            date('d-m-Y', strtotime($row['entry_date'])),
            $actions
        ];
    }

    echo json_encode(['data' => $data]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Income Records</title>
    <?php include 'inc/head.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />

</head>
<body class="bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-dark">Income Records</h4>
                <a href="add-income.php" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Income
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table id="incomeTable" class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Actual Amount</th>
                                <th>Received Amount</th>
                                <th>Balance Amount</th>
                                <th>Revenue</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#incomeTable').DataTable({
            ajax: '?ajax=true',
            columns: [
                { data: 0, className: 'text-center' },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5, className: 'text-end' },
                { data: 6, className: 'text-end' },
                { data: 7, className: 'text-end' },
                { data: 8, className: 'text-end' },
                { data: 9, className: 'text-center' },
                { data: 10, className: 'text-center', orderable: false }
            ],
            pageLength: 10,
            processing: true,
            responsive: true,
            language: { emptyTable: "No income data found." }
        });

        setTimeout(() => $(".alert").fadeOut(), 3000);
    });
    </script>
</body>
</html>