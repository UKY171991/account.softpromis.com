<?php
include 'config.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Check if the record exists
    $checkQuery = "SELECT * FROM income WHERE id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    
    if ($result->num_rows > 0) {
        // Proceed with deletion
        $deleteQuery = "DELETE FROM income WHERE id = ?";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $id);
        
        if ($stmtDelete->execute()) {
            echo "<script>alert('Income record deleted successfully!'); window.location.href='income.php';</script>";
        } else {
            echo "<script>alert('Error deleting record.'); window.location.href='income.php';</script>";
        }
    } else {
        echo "<script>alert('Record not found.'); window.location.href='income.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='income.php';</script>";
}
?>