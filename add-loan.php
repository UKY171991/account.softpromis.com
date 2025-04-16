<?php
include 'inc/auth.php';
include 'inc/config.php';

// Check if user is manager and redirect if true
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager') {
    header("Location: dashboard.php?error=You do not have permission to access this page");
    exit();
}

// Fetch all loan categories
$categories_sql = "SELECT id, name FROM loan_categories ORDER BY name";
$categories_result = $conn->query($categories_sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = $_POST['amount'];
    $paid = $_POST['paid'];
    $balance = $amount - $paid;

    $sql = "INSERT INTO loans (date, name, category, subcategory, amount, paid, balance) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $paid, $balance);
    
    if ($stmt->execute()) {
        header("Location: loan.php?message=Loan added successfully");
        exit();
    } else {
        $error = "Error adding loan: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Loan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content w-100">
            <?php include 'topbar.php'; ?>
            
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Add New Loan</h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>

                                <form action="" method="POST" id="addLoanForm">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="date" name="date" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="category" class="form-label">Category</label>
                                            <div class="input-group">
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    if ($categories_result->num_rows > 0) {
                                                        while($row = $categories_result->fetch_assoc()) {
                                                            echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subcategory" class="form-label">Subcategory</label>
                                            <div class="input-group">
                                                <select class="form-select" id="subcategory" name="subcategory" required>
                                                    <option value="">Select Category First</option>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="amount" class="form-label">Total Amount</label>
                                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="paid" class="form-label">Paid Amount</label>
                                            <input type="number" step="0.01" class="form-control" id="paid" name="paid" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Add Loan</button>
                                            <a href="loan.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subcategory Modal -->
    <div class="modal fade" id="addSubcategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubcategoryForm">
                        <div class="mb-3">
                            <label for="subcategoryName" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="subcategoryName" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveSubcategoryBtn">Save Subcategory</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load subcategories when category changes
            $('#category').change(function() {
                const category = $(this).val();
                if (category) {
                    $.ajax({
                        url: 'include/get-loan-subcategories.php',
                        type: 'POST',
                        data: { category: category },
                        success: function(response) {
                            $('#subcategory').html(response);
                        }
                    });
                } else {
                    $('#subcategory').html('<option value="">Select Category First</option>');
                }
            });

            // Add new category
            $('#saveCategoryBtn').click(function() {
                const categoryName = $('#categoryName').val();
                if (categoryName) {
                    $.ajax({
                        url: 'include/add-loan-category.php',
                        type: 'POST',
                        data: { name: categoryName },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                $('#category').append(`<option value="${categoryName}">${categoryName}</option>`);
                                $('#category').val(categoryName);
                                $('#addCategoryModal').modal('hide');
                                $('#categoryName').val('');
                            } else {
                                alert(result.message);
                            }
                        }
                    });
                }
            });

            // Add new subcategory
            $('#saveSubcategoryBtn').click(function() {
                const category = $('#category').val();
                const subcategoryName = $('#subcategoryName').val();
                if (category && subcategoryName) {
                    $.ajax({
                        url: 'include/add-loan-subcategory.php',
                        type: 'POST',
                        data: { 
                            category: category,
                            name: subcategoryName 
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                $('#subcategory').append(`<option value="${subcategoryName}">${subcategoryName}</option>`);
                                $('#subcategory').val(subcategoryName);
                                $('#addSubcategoryModal').modal('hide');
                                $('#subcategoryName').val('');
                            } else {
                                alert(result.message);
                            }
                        }
                    });
                } else {
                    alert('Please select a category first');
                }
            });

            // Calculate balance automatically
            $('#amount, #paid').on('input', function() {
                const amount = parseFloat($('#amount').val()) || 0;
                const paid = parseFloat($('#paid').val()) || 0;
                const balance = amount - paid;
                // You could display this somewhere if needed
            });
        });
    </script>
</body>
</html> 