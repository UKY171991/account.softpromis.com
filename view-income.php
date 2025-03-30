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
        $actions = '<a href="edit-income.php?id='.$row['id'].'" class="btn btn-sm btn-success">Edit</a> '
                 .'<a href="delete-income.php?id='.$row['id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this entry?\')">Delete</a>';

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
                    <div class="table-responsive">
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
        </div>
    </main>

    <!-- Scripts -->
    <?php include 'inc/scripts.php'; ?>

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