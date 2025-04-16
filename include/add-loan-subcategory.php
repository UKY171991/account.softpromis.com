<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if (isset($_POST['category']) && isset($_POST['name'])) {
    $category = $_POST['category'];
    $name = trim($_POST['name']);
    
    // Get category ID first
    $cat_sql = "SELECT id FROM loan_categories WHERE name = ?";
    $cat_stmt = $conn->prepare($cat_sql);
    $cat_stmt->bind_param("s", $category);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    
    if ($cat_row = $cat_result->fetch_assoc()) {
        $category_id = $cat_row['id'];
        
        // Check if subcategory already exists for this category
        $check_sql = "SELECT id FROM loan_subcategories WHERE category_id = ? AND name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $category_id, $name);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Subcategory already exists for this category'
            ]);
        } else {
            // Insert new subcategory
            $sql = "INSERT INTO loan_subcategories (category_id, name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $category_id, $name);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Subcategory added successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error adding subcategory: ' . $conn->error
                ]);
            }
            $stmt->close();
        }
        $check_stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Category not found'
        ]);
    }
    $cat_stmt->close();
}

$conn->close();
?> 