<?php
require 'db.php';

$uid = $_GET['uid'] ?? '';
$token = $_GET['token'] ?? '';

if (!$uid || !$token) {
    echo json_encode(['valid' => false, 'message' => 'Missing UID or Token']);
    exit();
}

// Validate UID + Token + Expiry
$stmt = $conn->prepare("SELECT id FROM users WHERE uid = ? AND token = ? AND token_expiry > NOW()");
$stmt->bind_param("ss", $uid, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    echo json_encode(['valid' => true]);
} else {
    echo json_encode(['valid' => false, 'message' => 'Invalid or expired link']);
}
?>
