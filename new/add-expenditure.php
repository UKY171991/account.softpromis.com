<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $name = ucfirst(trim($_POST['name']));
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    $amount = $_POST['amount'];
    $paid = $_POST['paid'];
    $balance = $amount - $paid;

    $stmt = $conn->prepare("INSERT INTO expenditures (date, name, category, subcategory, amount, paid, balance) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddd", $date, $name, $category, $subcategory, $amount, $paid, $balance);
    $stmt->execute();
    header("Location: expenditure.php?success=1");
    exit;
}
?>
<!-- Add Expenditure Form HTML here -->
