<?php
include 'inc/config.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM income_subcategories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $subcategory = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $category_id = intval($_POST['category_id']);
    $subcategory_name = trim($_POST['subcategory_name']);

    if (!empty($category_id) && !empty($subcategory_name)) {
        $update_query = "UPDATE income_subcategories SET category_id = ?, subcategory_name = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "isi", $category_id, $subcategory_name, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_msg'] = "Income sub-category updated successfully!";
        } else {
            $_SESSION['error_msg'] = "Error updating sub-category.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_msg'] = "All fields are required.";
    }

    header("Location: income-subcategory.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Income Sub-Category</title>
    <link rel="stylesheet" href="assets/css/material-dashboard.css?v=3.2.0">
</head>
<body>
    <div class="container py-4">
        <h4>Edit Income Sub-Category</h4>
        <form action="edit-subcategory.php" method="POST">
            <input type="hidden" name="id" value="<?= $subcategory['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Select Category</label>
                <select class="form-control" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php
                    $query = "SELECT * FROM income_categories ORDER BY category_name ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($subcategory['category_id'] == $row['id']) ? "selected" : "";
                        echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Sub-Category Name</label>
                <input type="text" class="form-control" name="subcategory_name" value="<?= $subcategory['subcategory_name'] ?>" required>
            </div>

            <button type="submit" class="btn bg-gradient-dark">Update Sub-Category</button>
            <a href="income-subcategory.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
