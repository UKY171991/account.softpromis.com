<?php
include 'inc/auth.php';
include 'inc/config.php';


if (isset($_GET['id'])) {
    $expenditure_id = intval($_GET['id']);
    
    $query = "SELECT * FROM expenditure WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $expenditure_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $expenditure = $result->fetch_assoc();
    } else {
        $_SESSION['error_msg'] = "Expenditure not found.";
        header("Location: expenditure-list.php");
        exit();
    }
} else {
    header("Location: expenditure-list.php");
    exit();
}


if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    $query = "SELECT * FROM expenditure_subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">-- Select Sub-Category --</option>';
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['subcategory_name']}</option>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Expenditure</title>
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
                <div class="col-12">
                    <h4 class="text-dark">Edit Expenditure</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mx-auto">
                    <?php if (isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_msg']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error_msg']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_msg']); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mt-4 d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-dark text-white d-flex align-items-center">
                            <h6 class="mb-0 text-white">Edit Expenditure</h6>
                        </div>
                        <div class="card-body">
                            <form action="update-expenditure.php" method="POST">
                            	<input type="hidden" name="id" value="<?= $expenditure['id']; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control border" value="<?= $expenditure['name']; ?>" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control border"  value="<?= $expenditure['phone']; ?>" name="phone" pattern="[0-9]{10}" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control border" name="category_id" id="categorySelect" required>
                                            <option value="">-- Select Category --</option>
                                            <?php
                                            $query = "SELECT * FROM expenditure_categories ORDER BY category_name ASC";
                                            $result = $conn->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['id'] == $expenditure['category_id']) ? 'selected' : '';
                        						echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sub-Category</label>
                                        <select class="form-control border" name="subcategory_id" id="subcategorySelect" required>
                                            <option value="">-- Select Sub-Category --</option>
                                            <?php
                                                $subcategory_query = "SELECT * FROM expenditure_subcategories WHERE category_id = {$expenditure['category_id']} ORDER BY subcategory_name ASC";
                                                $subcategory_result = mysqli_query($conn, $subcategory_query);
                                                while ($row = mysqli_fetch_assoc($subcategory_result)) {
                                                    $selected = ($row['id'] == $expenditure['subcategory_id']) ? 'selected' : '';
                                                    echo "<option value='{$row['id']}' $selected>{$row['subcategory_name']}</option>";
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Total Amount</label>
                                            <input type="number" class="form-control border" name="total_amount" value="<?= $expenditure['actual_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Paid Amount</label>
                                            <input type="number" class="form-control border" name="paid_amount" value="<?= $expenditure['paid_amount']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date of Entry</label>
                                            <input type="text" class="form-control border" name="date_of_entry" id="datepicker" value="<?= $expenditure['entry_date']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control border" name="description" required><?= $expenditure['description']; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn bg-gradient-dark">Submit</button>
                                </div>
                            </form>
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
    $(document).ready(function() {
        $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" });

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

    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({
                dateFormat: "dd-mm-yy", // Set format to dd-mm-yyyy
                changeMonth: true,  // Allows month selection
                changeYear: true,   // Allows year selection
                yearRange: "1900:+10" // Allows selection from 1900 to 10 years ahead
            });
        });
    </script>
</body>
</html>
