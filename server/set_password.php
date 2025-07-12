<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$token = $data['token'];
$password = $data['password'];

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, token_expiry = NULL WHERE id = ? AND token = ? AND token_expiry > NOW()");
$stmt->bind_param("sis", $hashed, $uid, $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid or expired token"]);
}
?>
