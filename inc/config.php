<?php
// Database connection
$host = "localhost";
$username = "u820431346_new_account";
$password = "9g/?fYqP+";
$database = "u820431346_new_account";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>