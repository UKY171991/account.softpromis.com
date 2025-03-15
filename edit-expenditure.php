<?php
include 'inc/auth.php';
include 'inc/config.php';

if (isset($_GET['id'])) {
    $expenditure_id = intval($_GET['id']);
    
    $query = "SELECT * FROM expenditures WHERE id = ?";
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
    <?php include 'inc/sidebar.php'; ?>
    <main>
        <?php include 'inc/topbar.php'; ?>
        <div class="container">
            <h4 class="text-dark">Edit Expenditure</h4>
            <form action="update-expenditure.php" method="POST">
                <input type="hidden" name="id" value="<?= $expenditure['id']; ?>">
                
                <label>Name</label>
                <input type="text" name="name" value="<?= $expenditure['name']; ?>" required>
                
                <label>Phone</label>
                <input type="text" name="phone" value="<?= $expenditure['phone']; ?>" pattern="[0-9]{10}" required>
                
                <label>Category</label>
                <select name="category_id" id="categorySelect" required>
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
                
                <label>Sub-Category</label>
                <select name="subcategory_id" id="subcategorySelect" required>
                    <option value="">-- Select Sub-Category --</option>
                </select>
                
                <label>Total Amount</label>
                <input type="number" name="total_amount" value="<?= $expenditure['total_amount']; ?>" required>
                
                <label>Paid Amount</label>
                <input type="number" name="paid_amount" value="<?= $expenditure['paid_amount']; ?>" required>
                
                <label>Date of Entry</label>
                <input type="text" name="date_of_entry" id="datepicker" value="<?= $expenditure['date_of_entry']; ?>" required>
                
                <label>Description</label>
                <textarea name="description" required><?= $expenditure['description']; ?></textarea>
                
                <button type="submit">Update</button>
            </form>
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
        
        let categoryId = "<?= $expenditure['category_id']; ?>";
        let subcategoryId = "<?= $expenditure['subcategory_id']; ?>";
        
        if (categoryId) {
            $.ajax({
                url: "fetch-expenditure-subcategories.php",
                type: "POST",
                data: { category_id: categoryId },
                success: function(response) {
                    $("#subcategorySelect").html(response);
                    $("#subcategorySelect").val(subcategoryId);
                }
            });
        }
        
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