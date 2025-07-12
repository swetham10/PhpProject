<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$price = $data['price'] ?? '';

if (empty($title) || empty($price)) {
    echo json_encode(['success' => false, 'message' => 'Missing title or price']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO products (title, description, price) VALUES (?, ?, ?)");
$stmt->bind_param("ssd", $title, $description, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $stmt->error]);
}
?>
