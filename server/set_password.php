<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'] ?? '';
$token = $data['token'] ?? '';
$password = $data['password'] ?? '';

// Validate inputs
if (!$uid || !$token || !$password) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

// ✅ Update WHERE uid instead of id
$stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, token_expiry = NULL WHERE uid = ? AND token = ? AND token_expiry > NOW()");
$stmt->bind_param("sss", $hashed, $uid, $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid or expired token"]);
}
?>
