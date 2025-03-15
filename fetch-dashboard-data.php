<?php
include 'inc/auth.php';
include 'inc/config.php';

// Fetch monthly expenditure data
$query = "
    SELECT 
        DATE_FORMAT(entry_date, '%Y-%m') AS month,
        SUM(actual_amount) AS total_expenditure,
        SUM(paid_amount) AS total_paid,
        SUM(balance_amount) AS total_balance
    FROM expenditure
    GROUP BY month
    ORDER BY month ASC
";

$result = $conn->query($query);

$labels = [];
$expenditure_data = [];
$paid_data = [];
$balance_data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $expenditure_data[] = $row['total_expenditure'];
    $paid_data[] = $row['total_paid'];
    $balance_data[] = $row['total_balance'];
}

// Return JSON response for Chart.js
echo json_encode([
    'labels' => $labels,
    'expenditure' => $expenditure_data,
    'paid' => $paid_data,
    'balance' => $balance_data
]);
?>
