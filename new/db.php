<?php
$servername = "localhost";
$username = "u820431346_new_account";
$password = "0xtO8dVJT3n*";
$dbname = "u820431346_new_account";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
