<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$price = $data['price'] ?? '';

if (!$id || !$title || !$price) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit();
}

$stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ? WHERE id = ?");
$stmt->bind_param("ssdi", $title, $description, $price, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}
?>
