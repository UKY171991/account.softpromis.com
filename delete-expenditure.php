<?php
include 'inc/auth.php';
include 'inc/config.php';

if (isset($_GET['id'])) {
    $expenditure_id = intval($_GET['id']);
    
    // Check if the expenditure exists
    $query = "SELECT id FROM expenditure WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $expenditure_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Proceed with deletion
        $deleteQuery = "DELETE FROM expenditure WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $expenditure_id);
        
        if ($deleteStmt->execute()) {
            $_SESSION['success_msg'] = "Expenditure record deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete expenditure record.";
        }
    } else {
        $_SESSION['error_msg'] = "Expenditure record not found.";
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
}

header("Location: view-expenditure.php");
exit();
?>
