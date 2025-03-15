<?php
include 'inc/auth.php';
include 'inc/config.php';

// Fetch monthly income and expenditure data
$query = "
    SELECT 
        DATE_FORMAT(transaction_date, '%Y-%m') AS month,
        SUM(CASE WHEN type = 'income' THEN total_amount ELSE 0 END) AS total_income,
        SUM(CASE WHEN type = 'expenditure' THEN total_amount ELSE 0 END) AS total_expenditure
    FROM transactions
    GROUP BY month
    ORDER BY month ASC
";

$result = $conn->query($query);

$labels = [];
$income_data = [];
$expenditure_data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['month'];
    $income_data[] = $row['total_income'];
    $expenditure_data[] = $row['total_expenditure'];
}

// Return JSON response
echo json_encode([
    'labels' => $labels,
    'income' => $income_data,
    'expenditure' => $expenditure_data
]);

?>
