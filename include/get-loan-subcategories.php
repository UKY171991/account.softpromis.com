<?php
include 'auth.php';
include 'config.php';

if (isset($_POST['category'])) {
    $category = $_POST['category'];
    
    // Get category ID first
    $cat_sql = "SELECT id FROM loan_categories WHERE name = ?";
    $cat_stmt = $conn->prepare($cat_sql);
    $cat_stmt->bind_param("s", $category);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    
    if ($cat_row = $cat_result->fetch_assoc()) {
        $category_id = $cat_row['id'];
        
        // Now get subcategories
        $sub_sql = "SELECT name FROM loan_subcategories WHERE category_id = ? ORDER BY name";
        $sub_stmt = $conn->prepare($sub_sql);
        $sub_stmt->bind_param("i", $category_id);
        $sub_stmt->execute();
        $sub_result = $sub_stmt->get_result();
        
        echo '<option value="">Select Subcategory</option>';
        while ($row = $sub_result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        
        $sub_stmt->close();
    }
    
    $cat_stmt->close();
}

$conn->close();
?> 