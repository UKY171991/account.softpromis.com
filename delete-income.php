<?php
include 'inc/auth.php'; // Ensure authentication
include 'inc/config.php'; // Database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Check if the record exists
    $checkQuery = "SELECT * FROM income WHERE id = ?";
    $stmtCheck = $conn->prepare($checkQuery);

    if ($stmtCheck) {
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        
        if ($result->num_rows > 0) {
            // Proceed with deletion
            $deleteQuery = "DELETE FROM income WHERE id = ?";
            $stmtDelete = $conn->prepare($deleteQuery);
            
            if ($stmtDelete) {
                $stmtDelete->bind_param("i", $id);
                
                if ($stmtDelete->execute()) {
                    $_SESSION['success_msg'] = "Income record deleted successfully!";
                } else {
                    $_SESSION['error_msg'] = "Error deleting record: " . $stmtDelete->error;
                }

                $stmtDelete->close();
            } else {
                $_SESSION['error_msg'] = "Failed to prepare delete query.";
            }
        } else {
            $_SESSION['error_msg'] = "Record not found.";
        }

        $stmtCheck->close();
    } else {
        $_SESSION['error_msg'] = "Failed to prepare check query.";
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
}

header("Location: view-income.php");
exit(); // Ensure script stops execution after redirect
?>
