<?php
header("Access-Control-Allow-Origin: *");
require 'db.php';
$result = $conn->query("SELECT * FROM products");
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
