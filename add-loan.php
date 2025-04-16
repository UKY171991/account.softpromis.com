<?php
include 'inc/auth.php';
include 'inc/config.php';

// Check if user is manager and redirect if true
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'manager') {
    header("Location: dashboard.php?error=You do not have permission to access this page");
    exit();
}

// Fetch loan categories
$sql = "SELECT id, name FROM loan_categories ORDER BY name";
$result = $conn->query($sql);
$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $name = $_POST['name'];
    $phone = $_POST['phone'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = $_POST['amount'];
    $paid = $_POST['paid'];
    $balance = $amount - $paid;

    // Get category ID
    $cat_sql = "SELECT id FROM loan_categories WHERE name = ?";
    $cat_stmt = $conn->prepare($cat_sql);
    $cat_stmt->bind_param("s", $category);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    
    if ($cat_result->num_rows === 0) {
        $error = "Selected category not found";
    } else {
        $category_id = $cat_result->fetch_assoc()['id'];
        
        // Get subcategory ID
        $sub_sql = "SELECT id FROM loan_subcategories WHERE category_id = ? AND name = ?";
        $sub_stmt = $conn->prepare($sub_sql);
        $sub_stmt->bind_param("is", $category_id, $subcategory);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();
        
        if ($sub_result->num_rows === 0) {
            $error = "Selected subcategory not found";
        } else {
            $subcategory_id = $sub_result->fetch_assoc()['id'];
            
            // Insert the loan
            $sql = "INSERT INTO loans (date, name, phone, description, category_id, subcategory_id, amount, paid, balance) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssiiiii", $date, $name, $phone, $description, $category_id, $subcategory_id, $amount, $paid, $balance);
            
            if ($stmt->execute()) {
                header("Location: loan.php?message=Loan added successfully");
                exit();
            } else {
                $error = "Error adding loan: " . $conn->error;
            }
            $stmt->close();
        }
        $sub_stmt->close();
    }
    $cat_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Loan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        html, body {
            height: 100%;
            overflow: auto;
        }

        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: #ffffff;
        }

        .sidebar .nav-link.active {
            background-color: #495057;
        }

        .main-content {
            margin-left: 250px;
            overflow-y: auto;
            height: 100vh;
        }

        .top-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
        }

        .form-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-add-category {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }

        .modal-content {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        .alert {
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Top Navbar -->
            <?php include 'topbar.php'; ?>

            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Add New Loan</h5>
                    <a href="loan.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Loans</a>
                </div>

                <div class="form-container">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form id="addLoanForm" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo htmlspecialchars($category['name']); ?>">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="subcategory" class="form-label">Sub-category <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" id="subcategory" name="subcategory" required>
                                        <option value="">Select Category First</option>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary btn-add-category" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="amount" class="form-label">Total Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="paid" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="paid" name="paid" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="balance" class="form-label">Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="balance" name="balance" step="0.01" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Loan
                            </button>
                        </div>
                    </form>
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
                    <div class="mb-3">
                        <label for="newCategory" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="newCategory">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategory">Save Category</button>
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
                    <div class="mb-3">
                        <label for="subcategoryCategory" class="form-label">Category</label>
                        <select class="form-select" id="subcategoryCategory" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['name']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="newSubcategory" class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" id="newSubcategory">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSubcategory">Save Subcategory</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/responsive.js"></script>
    <script>
        $(document).ready(function() {
            // Calculate balance automatically
            $('#amount, #paid').on('input', function() {
                const amount = parseFloat($('#amount').val()) || 0;
                const paid = parseFloat($('#paid').val()) || 0;
                $('#balance').val((amount - paid).toFixed(2));
            });

            // Load subcategories when category changes
            $('#category').change(function() {
                const category = $(this).val();
                if (category) {
                    $.ajax({
                        url: 'include/get-loan-subcategories.php',
                        type: 'POST',
                        data: { category: category },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#subcategory').html(response.html);
                            } else {
                                alert(response.message || 'Error loading subcategories');
                            }
                        },
                        error: function() {
                            alert('Error loading subcategories');
                        }
                    });
                } else {
                    $('#subcategory').html('<option value="">Select Category First</option>');
                }
            });

            // Add new category
            $('#saveCategory').click(function() {
                const category = $('#newCategory').val().trim();
                if (category) {
                    $.ajax({
                        url: 'include/add-loan-category.php',
                        type: 'POST',
                        data: { category: category },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Add to category dropdowns
                                $('#category').append(
                                    $('<option></option>').val(category).text(category)
                                );
                                // Select the new category
                                $('#category').val(category).trigger('change');
                                // Close modal and clear input
                                $('#addCategoryModal').modal('hide');
                                $('#newCategory').val('');
                            } else {
                                alert(response.message || 'Error adding category');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            alert('Error adding category. Please try again.');
                        }
                    });
                } else {
                    alert('Please enter a category name');
                }
            });

            // Add new subcategory
            $('#saveSubcategory').click(function() {
                const category = $('#subcategoryCategory').val();
                const subcategory = $('#newSubcategory').val().trim();
                if (!category) {
                    alert('Please select a category first');
                    return;
                }
                if (!subcategory) {
                    alert('Please enter a subcategory name');
                    return;
                }
                
                $.ajax({
                    url: 'include/add-loan-subcategory.php',
                    type: 'POST',
                    data: { 
                        category: category,
                        subcategory: subcategory
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // If current category matches, add to subcategory dropdown
                            if ($('#category').val() === category) {
                                $('#subcategory').append(
                                    $('<option></option>').val(subcategory).text(subcategory)
                                );
                                // Select the new subcategory
                                $('#subcategory').val(subcategory);
                            }
                            // Close modal and clear input
                            $('#addSubcategoryModal').modal('hide');
                            $('#newSubcategory').val('');
                        } else {
                            alert(response.message || 'Error adding subcategory');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Error adding subcategory. Please try again.');
                    }
                });
            });

            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            $('#date').val(today);
        });
    </script>
</body>
</html>

<?php
$conn->close();
?> 