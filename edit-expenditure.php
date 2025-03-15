<?php
// edit-expenditure.php
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
        
        // Fetch subcategories for current category
        $sub_query = "SELECT * FROM expenditure_subcategories WHERE category_id = ?";
        $sub_stmt = $conn->prepare($sub_query);
        $sub_stmt->bind_param("i", $expenditure['category_id']);
        $sub_stmt->execute();
        $subcategories = $sub_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
    } else {
        $_SESSION['error_msg'] = "Expenditure not found.";
        header("Location: expenditure-list.php");
        exit();
    }
} else {
    header("Location: expenditure-list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
</head>
<body>
    <!-- ... existing body structure ... -->

    <!-- Updated form fields with pre-populated data -->
    <form action="update-expenditure.php" method="POST">
        <input type="hidden" name="id" value="<?= $expenditure['id'] ?>">
        
        <div class="row">
            <!-- ... other fields ... -->
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <input type="number" class="form-control border" 
                           name="total_amount" 
                           value="<?= $expenditure['total_amount'] ?>" 
                           required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Paid Amount</label>
                    <input type="number" class="form-control border" 
                           name="paid_amount" 
                           value="<?= $expenditure['paid_amount'] ?>" 
                           required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Date of Entry</label>
                    <input type="text" class="form-control border" 
                           name="date_of_entry" 
                           id="datepicker" 
                           value="<?= date('d-m-Y', strtotime($expenditure['date_of_entry'])) ?>" 
                           required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control border" 
                              name="description" 
                              required><?= $expenditure['description'] ?></textarea>
                </div>
            </div>
        </div>

        <!-- Updated subcategory dropdown initialization -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Sub-Category</label>
                <select class="form-control border" name="subcategory_id" id="subcategorySelect" required>
                    <option value="">-- Select Sub-Category --</option>
                    <?php foreach ($subcategories as $sub): ?>
                        <option value="<?= $sub['id'] ?>"
                            <?= ($sub['id'] == $expenditure['subcategory_id']) ? 'selected' : '' ?>>
                            <?= $sub['subcategory_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- ... rest of the form ... -->
    </form>

    <!-- Updated JavaScript -->
    <script>
    $(document).ready(function() {
        // Initialize datepicker with correct format
        $("#datepicker").datepicker({ 
            dateFormat: "dd-mm-yy",
            defaultDate: "<?= date('d-m-Y', strtotime($expenditure['date_of_entry'])) ?>"
        });

        // Handle category change
        $("#categorySelect").change(function() {
            let categoryId = $(this).val();
            $("#subcategorySelect").html('<option value="">Loading...</option>');

            $.ajax({
                url: "fetch-expenditure-subcategories.php",
                type: "POST",
                data: { category_id: categoryId },
                success: function(response) {
                    $("#subcategorySelect").html(response);
                    // Re-select current subcategory if same category
                    <?php if (isset($expenditure['subcategory_id'])): ?>
                        if (categoryId == <?= $expenditure['category_id'] ?>) {
                            $("#subcategorySelect").val(<?= $expenditure['subcategory_id'] ?>);
                        }
                    <?php endif; ?>
                },
                error: function() {
                    $("#subcategorySelect").html('<option value="">Error loading subcategories</option>');
                }
            });
        });
    });
    </script>
</body>
</html>