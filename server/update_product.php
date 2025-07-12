<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$title = $data['title'];
$description = $data['description'];
$price = $data['price'];

$stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ? WHERE id = ?");
$stmt->bind_param("ssdi", $title, $description, $price, $id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
