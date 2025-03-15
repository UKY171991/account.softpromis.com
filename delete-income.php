<?php
include 'auth.php'; // Database connection
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
        	$_SESSION['success_msg'] = "Income record deleted successfully!";
            header("Location: view-income.php");
        } else {
        	$_SESSION['error_msg'] = "Error deleting record: " . mysqli_error($conn);
           header("Location: view-income.php");
        }
    } else {
        $_SESSION['error_msg'] = "Record not found.";
        header("Location: view-income.php");
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='income.php';</script>";
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: view-income.php");
}
?>