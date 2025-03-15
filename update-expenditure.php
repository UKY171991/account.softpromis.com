<?php
session_start();
include 'inc/auth.php';
include 'inc/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = strtoupper(trim($_POST['name']));
    $phone = trim($_POST['phone']);
    $description = ucfirst(trim($_POST['description']));
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $actual_amount = floatval($_POST['total_amount']);
    $paid_amount = floatval($_POST['paid_amount']);
    $balance_amount = $actual_amount - $paid_amount;
    $entry_date = trim($_POST['date_of_entry']);

    // Convert dd-mm-yyyy to YYYY-MM-DD
    $date_parts = explode("-", $entry_date);
    if (count($date_parts) == 3) {
        $date_of_entry = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];
    } else {
        $_SESSION['error_msg'] = "Invalid date format.";
        header("Location: edit-expenditure.php?id=$id");
        exit();
    }

    // Update expenditure in database
    $stmt = $conn->prepare("UPDATE expenditure SET name=?, phone=?, description=?, category_id=?, subcategory_id=?, actual_amount=?, paid_amount=?, balance_amount=?, entry_date=? WHERE id=?");
    $stmt->bind_param("sssiiiddsi", $name, $phone, $description, $category_id, $subcategory_id, $actual_amount, $paid_amount, $balance_amount, $date_of_entry, $id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Expenditure updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update expenditure. Error: " . $stmt->error;
    }

    header("Location: manage-expenditure.php");
    exit();
}
?>
