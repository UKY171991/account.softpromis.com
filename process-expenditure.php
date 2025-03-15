<?php
session_start();
include 'inc/auth.php';
include 'inc/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = strtoupper(trim($_POST['name']));
    $phone = trim($_POST['phone']);
    $description = ucfirst(trim($_POST['description']));
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $actual_amount = floatval($_POST['total_amount']); // Changed to match 'actual_amount'
    $paid_amount = floatval($_POST['paid_amount']);
    $balance_amount = $actual_amount - $paid_amount;

    $entry_date = trim($_POST['date_of_entry']);

    // Convert dd-mm-yyyy to YYYY-MM-DD for MySQL
    $entry_date = date("Y-m-d", strtotime($entry_date));


    // Validate required fields
    if (empty($name) || empty($phone) || empty($description) || empty($category_id) || empty($subcategory_id) || empty($actual_amount) || empty($paid_amount) || empty($date_of_entry)) {
        $_SESSION['error_msg'] = "All fields are required.";
        header("Location: add-expenditure.php");
        exit();
    }

    // Validate phone number (10 digits)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['error_msg'] = "Invalid phone number. Must be 10 digits.";
        header("Location: add-expenditure.php");
        exit();
    }

    // Ensure the category exists
    $categoryCheck = $conn->prepare("SELECT id FROM expenditure_categories WHERE id = ?");
    $categoryCheck->bind_param("i", $category_id);
    $categoryCheck->execute();
    $categoryCheckResult = $categoryCheck->get_result();

    // Ensure the subcategory exists and belongs to the category
    $subcategoryCheck = $conn->prepare("SELECT id FROM expenditure_subcategories WHERE id = ? AND category_id = ?");
    $subcategoryCheck->bind_param("ii", $subcategory_id, $category_id);
    $subcategoryCheck->execute();
    $subcategoryCheckResult = $subcategoryCheck->get_result();

    if ($categoryCheckResult->num_rows == 0 || $subcategoryCheckResult->num_rows == 0) {
        $_SESSION['error_msg'] = "Invalid Category or Sub-Category.";
        header("Location: add-expenditure.php");
        exit();
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO expenditure (name, phone, description, category_id, subcategory_id, actual_amount, paid_amount, balance_amount, entry_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiidds", $name, $phone, $description, $category_id, $subcategory_id, $actual_amount, $paid_amount, $balance_amount, $date_of_entry);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Expenditure added successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to add expenditure. Error: " . $stmt->error;
    }

    // Redirect back to the form
    header("Location: add-expenditure.php");
    exit();
} else {
    // If accessed without form submission, redirect
    $_SESSION['error_msg'] = "Invalid request.";
    header("Location: add-expenditure.php");
    exit();
}
?>
