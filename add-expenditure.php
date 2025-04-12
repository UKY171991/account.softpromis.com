<?php
include 'inc/auth.php';
include 'inc/config.php';

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
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Expenditure</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <main>
        <div class="container">
            <h4>Add Expenditure1</h4>
            <form action="process-expenditure.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select class="form-control" name="category_id" id="categorySelect" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        $query = "SELECT * FROM expenditure_categories ORDER BY category_name ASC";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub-Category</label>
                    <select class="form-control" name="subcategory_id" id="subcategorySelect" required>
                        <option value="">-- Select Sub-Category --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <input type="number" class="form-control" name="total_amount" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Paid Amount</label>
                    <input type="number" class="form-control" name="paid_amount" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Entry</label>
                    <input type="text" class="form-control" name="date_of_entry" id="datepicker" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" });

            // Fetch subcategories dynamically
            $("#categorySelect").change(function() {
                let categoryId = $(this).val();
                $("#subcategorySelect").html('<option value="">Loading...</option>');

                if (categoryId !== "") {
                    $.ajax({
                        url: "add-expenditure.php",
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
</body>
</html>
