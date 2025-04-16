<?php
include '../inc/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $category = trim($_POST['category']);
    
    // Get subcategories for the selected category
    $sql = "SELECT DISTINCT subcategory FROM loan_categories WHERE category = ? AND subcategory IS NOT NULL ORDER BY subcategory";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $html = '<option value="">Select Subcategory</option>';
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subcategory = htmlspecialchars($row['subcategory']);
            $html .= "<option value=\"{$subcategory}\">{$subcategory}</option>";
        }
        echo json_encode([
            'success' => true,
            'html' => $html
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'html' => '<option value="">No subcategories found</option>'
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}

$conn->close();
?> 