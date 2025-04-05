<?php
include 'inc/auth.php';
include 'inc/config.php';

$filter_month = $_GET['month'] ?? '';
$filter_year = $_GET['year'] ?? '';
$filter_type = $_GET['type'] ?? 'income';

$table = $filter_type === 'expenditure' ? 'expenditures' : 'income';

$query = "SELECT * FROM $table WHERE 1";
if ($filter_month) $query .= " AND MONTH(date) = '$filter_month'";
if ($filter_year) $query .= " AND YEAR(date) = '$filter_year'";

$result = $conn->query($query);
?>
<!-- Report Filter Form and Table Display -->
