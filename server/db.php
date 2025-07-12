<?php
$host = "localhost";
$user = "root";
$pass = "1234"; // or your MySQL root password
$dbname = "testdb"; // change to your DB name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
