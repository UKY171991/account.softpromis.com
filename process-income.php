<?php
include 'inc/auth.php';
include 'inc/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $actual_amount = floatval($_POST['actual_amount']);
    $received_amount = floatval($_POST['received_amount']);
    $balance_amount = $actual_amount - $received_amount;
    $entry_date = $_POST['date_of_entry'];

    if (!empty($name) && !empty($category_id) && !empty($subcategory_id) && !empty($actual_amount) && !empty($entry_date)) {
        $query = "INSERT INTO income (name, description, category_id, subcategory_id, actual_amount, received_amount, balance_amount, entry_date)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssiidids", $name, $description, $category_id, $subcategory_id, $actual_amount, $received_amount, $balance_amount, $entry_date);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_msg'] = "Income entry added successfully!";
        } else {
            $_SESSION['error_msg'] = "Error adding income entry!";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_msg'] = "All fields are required!";
    }

    header("Location: add-income.php");
    exit();
}
?>
