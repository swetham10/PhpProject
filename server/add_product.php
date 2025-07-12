<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$description = $data['description'];
$price = $data['price'];

$stmt = $conn->prepare("INSERT INTO products (title, description, price) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $title, $description, $price);
$stmt->execute();

echo json_encode(["success" => true]);
?>
