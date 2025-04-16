<?php
include '../inc/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    $subcategory = trim($_POST['subcategory']);
    
    if (empty($category) || empty($subcategory)) {
        echo json_encode([
            'success' => false,
            'message' => 'Category and subcategory names cannot be empty'
        ]);
        exit;
    }

    // Check if subcategory already exists for this category
    $check_sql = "SELECT subcategory FROM loan_categories WHERE category = ? AND subcategory = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $category, $subcategory);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Subcategory already exists for this category'
        ]);
        exit;
    }
    
    // Insert new subcategory
    $sql = "INSERT INTO loan_categories (category, subcategory) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $category, $subcategory);
    
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
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?> 