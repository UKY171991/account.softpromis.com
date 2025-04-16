<?php
include '../inc/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);
    
    if (empty($category)) {
        echo json_encode([
            'success' => false,
            'message' => 'Category name cannot be empty'
        ]);
        exit;
    }

    // Check if category already exists
    $check_sql = "SELECT category FROM loan_categories WHERE category = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $category);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Category already exists'
        ]);
        exit;
    }
    
    // Insert new category
    $sql = "INSERT INTO loan_categories (category) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Category added successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error adding category: ' . $conn->error
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