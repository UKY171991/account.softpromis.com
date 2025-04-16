<?php
include 'auth.php';
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete the loan
    $sql = "DELETE FROM loans WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../loan.php?message=Loan deleted successfully");
    } else {
        header("Location: ../loan.php?error=Error deleting loan: " . $conn->error);
    }
    
    $stmt->close();
} else {
    header("Location: ../loan.php?error=No loan ID provided");
}

$conn->close();
?> 