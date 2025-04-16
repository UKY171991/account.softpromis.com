<?php
include 'auth.php';
include 'config.php';

header('Content-Type: application/json');

if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
    
    // Check if category already exists
    $check_sql = "SELECT id FROM loan_categories WHERE name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Category already exists'
        ]);
    } else {
        // Insert new category
        $sql = "INSERT INTO loan_categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        
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
    }
    $check_stmt->close();
}

$conn->close();
?> 