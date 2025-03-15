<?php
include 'inc/auth.php';
include 'inc/config.php';

// Fetch monthly income data
$query = "
    SELECT 
        DATE_FORMAT(entry_date, '%Y-%m') AS month,
        SUM(actual_amount) AS total_income,
        SUM(received_amount) AS total_received,
        SUM(balance_amount) AS total_balance
    FROM income
    GROUP BY month
    ORDER BY month ASC
";

$result = $conn->query($query);

$labels = [];
$income_data = [];
$received_data = [];
$balance_data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $income_data[] = $row['total_income'];
    $received_data[] = $row['total_received'];
    $balance_data[] = $row['total_balance'];
}

// Return JSON response for Chart.js
echo json_encode([
    'labels' => $labels,
    'income' => $income_data,
    'received' => $received_data,
    'balance' => $balance_data
]);
?>
