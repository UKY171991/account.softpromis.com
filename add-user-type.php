<?php
include 'inc/auth.php'; // Include your database connection
include 'inc/config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = trim($_POST['type']);

    if (!empty($type)) {
        $query = "INSERT INTO use_type (type) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $type);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "User type added successfully.";
        } else {
            $_SESSION['error_msg'] = "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_msg'] = "User type cannot be empty.";
    }

    header("Location: user-type.php"); // Redirect to the form page
    exit();
}
?>