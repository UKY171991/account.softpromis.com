<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = strtoupper(trim($_POST['name']));
    $phone = trim($_POST['phone']);
    $category_id = intval($_POST['category_id']);
    $total_amount = floatval($_POST['total_amount']);
    $paid_amount = floatval($_POST['paid_amount']);
    $date_of_entry = date('Y-m-d', strtotime($_POST['date_of_entry']));

    if ($total_amount < $paid_amount) {
        $_SESSION['error_msg'] = "Paid amount cannot exceed total amount.";
        header("Location: edit-expenditure.php?id=$id");
        exit();
    }

    $query = "UPDATE expenditures SET name = ?, phone = ?, category_id = ?, total_amount = ?, paid_amount = ?, date_of_entry = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiddsi", $name, $phone, $category_id, $total_amount, $paid_amount, $date_of_entry, $id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Expenditure updated successfully.";
        header("Location: expenditure.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Failed to update expenditure.";
        header("Location: edit-expenditure.php?id=$id");
        exit();
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: expenditure.php");
    exit();
}
