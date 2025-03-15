<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $use_type = intval($_POST['use_type']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (!empty($use_type) && !empty($username) && !empty($password)) {
        // Hash the password before storing it
        $hashed_password = md5($password); // You can replace md5 with password_hash() for better security

        // Insert into the database
        $query = "INSERT INTO users (use_type, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $use_type, $username, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "User added successfully.";
        } else {
            $_SESSION['error_msg'] = "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_msg'] = "All fields are required.";
    }

    header("Location: add-user.php");
    exit();
}
?>