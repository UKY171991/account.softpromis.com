<?php
include 'inc/auth.php';
include 'inc/config.php';

// AJAX Data Fetch
if (isset($_GET['ajax'])) {
    $query = "SELECT e.id, e.name, e.phone, e.description, c.category_name, s.subcategory_name, 
                     e.actual_amount, e.paid_amount, (e.actual_amount - e.paid_amount) AS balance_amount, e.entry_date 
              FROM expenditure e
              INNER JOIN expenditure_categories c ON e.category_id = c.id
              INNER JOIN expenditure_subcategories s ON e.subcategory_id = s.id
              ORDER BY e.id DESC";

    $result = $conn->query($query);
    $data = [];
    $count = 1;

    while ($row = $result->fetch_assoc()) {
        $actions = '<a href="edit-expenditure.php?id='.$row['id'].'" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a> '
                 .'<a href="delete-expenditure.php?id='.$row['id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this record?\')"><i class="fa fa-trash"></i></a>';

        $data[] = [
            $count++, 
            htmlspecialchars($row['name']),
            htmlspecialchars($row['phone']),
            htmlspecialchars($row['description']),
            htmlspecialchars($row['category_name']),
            htmlspecialchars($row['subcategory_name']),
            number_format($row['actual_amount'], 2),
            number_format($row['paid_amount'], 2),
            number_format($row['balance_amount'], 2),
            date("d-m-Y", strtotime($row['entry_date'])),
            $actions
        ];
    }

    echo json_encode(["data" => $data]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/head.php'; ?>
    <title>View Expenditure</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body class="g-sidenav-show bg-gray-100">
    <?php include 'inc/sidebar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <?php include 'inc/topbar.php'; ?>

        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-dark">Expenditure Records</h4>
                <a href="add-expenditure.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Expenditure</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="expenditureTable" class="table table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th>Actual Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <?php include 'inc/scripts.php'; ?>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#expenditureTable').DataTable({
            ajax: '?ajax=true',
            columns: [
                { data: 0, className: 'text-center' },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, className: 'text-end' },
                { data: 7, className: 'text-end' },
                { data: 8, className: 'text-end' },
                { data: 9, className: 'text-center' },
                { data: 10, className: 'text-center', orderable: false }
            ],
            pageLength: 10,
            processing: true,
            responsive: true,
            language: { emptyTable: "No expenditure data found." }
        });

        setTimeout(function() { $(".alert").fadeOut("slow"); }, 3000);
    });
    </script>
</body>
</html>