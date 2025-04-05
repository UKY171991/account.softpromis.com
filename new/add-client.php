<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = ucfirst(trim($_POST['name']));
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = ucfirst(trim($_POST['address']));

    $stmt = $conn->prepare("INSERT INTO clients (name, phone, email, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $address);
    $stmt->execute();
    header("Location: client.php?success=1");
    exit;
}
?>
<!-- Add Client Form HTML here -->
