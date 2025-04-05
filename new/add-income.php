<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = $_POST['amount'];
    $received = $_POST['received'];
    $balance = $amount - $received;

    $stmt = $conn->prepare("INSERT INTO income (date, name, category, subcategory, amount, received, balance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $received, $balance);
    $stmt->execute();
    header("Location: income.php?success=1");
    exit;
}
?>
<!-- Add Income Form HTML here -->
