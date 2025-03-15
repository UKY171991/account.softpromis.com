<?php
session_start();
include 'inc/auth.php';
include 'inc/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $name = strtoupper(trim($_POST['name']));
    $phone = trim($_POST['phone']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $total_amount = floatval($_POST['total_amount']);
    $paid_amount = floatval($_POST['paid_amount']);
    $balance_amount = $total_amount - $paid_amount;
    //$date_of_entry = date("Y-m-d", strtotime($_POST['date_of_entry']));
    $entry_date = trim($_POST['date_of_entry']);

    // Convert dd-mm-yyyy to YYYY-MM-DD for MySQL
    $date_of_entry = date("Y-m-d", strtotime($entry_date));

    // Validate required fields
    if (empty($name) || empty($phone) || empty($category_id) || empty($subcategory_id) || empty($total_amount) || empty($paid_amount) || empty($date_of_entry)) {
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

    print_r($_POST);  die;

    // Ensure the category and subcategory exist
    $categoryCheck = $conn->prepare("SELECT id FROM expenditure_categories WHERE id = ?");
    $categoryCheck->bind_param("i", $category_id);
    $categoryCheck->execute();
    $categoryCheckResult = $categoryCheck->get_result();


   print_r($_POST);  die;

    $subcategoryCheck = $conn->prepare("SELECT id FROM expenditure_subcategories WHERE id = ? AND category_id = ?");
    $subcategoryCheck->bind_param("ii", $subcategory_id, $category_id);
    $subcategoryCheck->execute();
    $subcategoryCheckResult = $subcategoryCheck->get_result();

   print_r($_POST);  die;

    if ($categoryCheckResult->num_rows == 0 || $subcategoryCheckResult->num_rows == 0) {
        $_SESSION['error_msg'] = "Invalid Category or Sub-Category.";
        header("Location: add-expenditure.php");
        exit();
    }

       print_r($_POST);  die;

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO expenditures (name, phone, category_id, subcategory_id, total_amount, paid_amount, balance_amount, entry_date) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiidds", $name, $phone, $category_id, $subcategory_id, $total_amount, $paid_amount, $balance_amount, $date_of_entry);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Expenditure added successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to add expenditure. Please try again.";
    }

       print_r($_POST);  die;

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
