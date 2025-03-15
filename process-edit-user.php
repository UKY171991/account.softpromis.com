<?php
include 'inc/auth.php';
include 'inc/config.php';

// Check if ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: list-users.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error_msg'] = "User not found.";
    header("Location: list-users.php");
    exit();
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $use_type = intval($_POST['use_type']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($use_type) && !empty($username)) {
        if (!empty($password)) {
            // Hash new password
            $hashed_password = md5($password); // Consider using password_hash() for better security
            $updateQuery = "UPDATE users SET use_type = ?, username = ?, password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("issi", $use_type, $username, $hashed_password, $id);
        } else {
            $updateQuery = "UPDATE users SET use_type = ?, username = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("isi", $use_type, $username, $id);
        }

        if ($updateStmt->execute()) {
            $_SESSION['success_msg'] = "User updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error: " . $conn->error;
        }

        $updateStmt->close();
        header("Location: list-users.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Username and User Type are required.";
    }
}
?>