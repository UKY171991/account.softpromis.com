<?php
include 'inc/auth.php';
include 'inc/config.php';

// Check if ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: list-user-type.php");
    exit();
}

$id = intval($_GET['id']);

// Check if the user type exists
$query = "SELECT * FROM use_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_msg'] = "User type not found.";
    header("Location: list-user-type.php");
    exit();
}

// Delete the user type
$deleteQuery = "DELETE FROM use_type WHERE id = ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("i", $id);

if ($deleteStmt->execute()) {
    $_SESSION['success_msg'] = "User type deleted successfully.";
} else {
    $_SESSION['error_msg'] = "Error: " . $conn->error;
}

$deleteStmt->close();
header("Location: list-user-type.php");
exit();
?>
