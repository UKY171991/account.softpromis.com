<?php
session_start();
include 'inc/auth.php';
include 'inc/config.php';

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid Expenditure ID.";
    header("Location: manage-expenditure.php");
    exit();
}

$expenditure_id = intval($_GET['id']);

// Fetch expenditure details
$query = "SELECT * FROM expenditure WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $expenditure_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error_msg'] = "Expenditure not found.";
    header("Location: manage-expenditure.php");
    exit();
}

$expenditure = $result->fetch_assoc();
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

<body>
<div class="container mt-5">
    <h4 class="mb-4">Edit Expenditure</h4>

    <form action="update-expenditure.php" method="POST">
        <input type="hidden" name="id" value="<?= $expenditure['id']; ?>">

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?= $expenditure['name']; ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" value="<?= $expenditure['phone']; ?>" pattern="[0-9]{10}" maxlength="10" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select class="form-control" name="category_id" id="categorySelect" required>
                    <option value="">-- Select Category --</option>
                    <?php
                    $category_query = "SELECT * FROM expenditure_categories ORDER BY category_name ASC";
                    $category_result = $conn->query($category_query);
                    while ($row = $category_result->fetch_assoc()) {
                        $selected = ($row['id'] == $expenditure['category_id']) ? "selected" : "";
                        echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sub-Category</label>
                <select class="form-control" name="subcategory_id" id="subcategorySelect" required>
                    <option value="">-- Select Sub-Category --</option>
                    <?php
                    $subcategory_query = "SELECT * FROM expenditure_subcategories WHERE category_id = ?";
                    $subcategory_stmt = $conn->prepare($subcategory_query);
                    $subcategory_stmt->bind_param("i", $expenditure['category_id']);
                    $subcategory_stmt->execute();
                    $subcategory_result = $subcategory_stmt->get_result();

                    while ($row = $subcategory_result->fetch_assoc()) {
                        $selected = ($row['id'] == $expenditure['subcategory_id']) ? "selected" : "";
                        echo "<option value='{$row['id']}' $selected>{$row['subcategory_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Total Amount</label>
                <input type="number" class="form-control" name="actual_amount" value="<?= $expenditure['actual_amount']; ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Paid Amount</label>
                <input type="number" class="form-control" name="paid_amount" value="<?= $expenditure['paid_amount']; ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date of Entry</label>
                <input type="text" class="form-control" name="entry_date" id="datepicker" value="<?= date("d-m-Y", strtotime($expenditure['entry_date'])); ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" required><?= $expenditure['description']; ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Update</button>
        <a href="manage-expenditure.php" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>

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
            $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" });
        });
    </script>
</body>
</html>
