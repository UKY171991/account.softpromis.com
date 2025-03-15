<?php
include 'inc/auth.php';
include 'inc/config.php';

if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    $query = "SELECT * FROM expenditure_subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<option value="">-- Select Sub-Category --</option>';
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['subcategory_name']}</option>";
        }
    } else {
        echo '<option value="">No subcategories found</option>';
    }
    exit();
}
?>
