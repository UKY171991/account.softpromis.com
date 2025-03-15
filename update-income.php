<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $actual_amount = floatval($_POST['actual_amount']);
    $received_amount = floatval($_POST['received_amount']);
    $revenue = floatval($_POST['revenue']);
    $balance_amount = $actual_amount - $received_amount;
    //$date_of_entry = mysqli_real_escape_string($conn, $_POST['date_of_entry']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $date_of_entry = trim($_POST['date_of_entry']);

    // Convert dd-mm-yyyy to YYYY-MM-DD for MySQL
    $date_of_entry = date("Y-m-d", strtotime($date_of_entry));

    $query = "UPDATE income SET 
                name = '$name', 
                phone = '$phone', 
                category_id = '$category_id', 
                subcategory_id = '$subcategory_id', 
                actual_amount = '$actual_amount', 
                received_amount = '$received_amount', 
                revenue = '$revenue', 
                balance_amount = '$balance_amount', 
                entry_date = '$date_of_entry', 
                description = '$description' 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_msg'] = "Income record updated successfully.";
    } else {
        $_SESSION['error_msg'] = "Failed to update income record: " . mysqli_error($conn);
    }

    header("Location: view-income.php");
    exit();
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: view-income.php");
    exit();
}

?>