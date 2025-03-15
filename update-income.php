<?php
include 'inc/auth.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        $_SESSION['error_msg'] = "Invalid income entry.";
        header("Location: income-list.php");
        exit();
    }

    $id = intval($_POST['id']);

    // Validate and sanitize inputs
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $actual_amount = floatval($_POST['actual_amount']);
    $received_amount = floatval($_POST['received_amount']);
    $revenue = floatval($_POST['revenue']);
    $balance_amount = $actual_amount - $received_amount;
    $description = trim(mysqli_real_escape_string($conn, $_POST['description']));

    // Validate date format and convert to YYYY-MM-DD
    $entry_date = trim($_POST['date_of_entry']);
    if (!empty($entry_date)) {
        $date_of_entry = date("Y-m-d", strtotime($entry_date));
    } else {
        $_SESSION['error_msg'] = "Invalid date format.";
        header("Location: edit-income.php?id=$id");
        exit();
    }

    // Check if record exists before updating
    $check_query = "SELECT id FROM income WHERE id = $id";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) == 0) {
        $_SESSION['error_msg'] = "Income record not found.";
        header("Location: income-list.php");
        exit();
    }

    // Update query
    $query = "UPDATE income SET 
                name = '$name', 
                phone = '$phone', 
                category_id = $category_id, 
                subcategory_id = $subcategory_id, 
                actual_amount = $actual_amount, 
                received_amount = $received_amount, 
                revenue = $revenue, 
                balance_amount = $balance_amount, 
                date_of_entry = '$date_of_entry', 
                description = '$description' 
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_msg'] = "Income record updated successfully.";
        header("Location: view-income.php?id=$id");
        exit();
    } else {
        $_SESSION['error_msg'] = "Failed to update income record: " . mysqli_error($conn);
        header("Location: edit-income.php?id=$id");
        exit();
    }
} else {
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: income-list.php");
    exit();
}
?>
