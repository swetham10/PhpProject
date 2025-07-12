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
$email = $data['email'] ?? '';
$otp = $data['otp'] ?? '';
$newPassword = $data['password'] ?? '';

if (!$email || !$otp || !$newPassword) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit();
}

$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

// Validate OTP and expiry
$stmt = $conn->prepare("SELECT otp_expiry FROM users WHERE email = ? AND otp = ?");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid OTP or email"]);
    exit();
}

$row = $result->fetch_assoc();
$otpExpiry = $row['otp_expiry'];

if (strtotime($otpExpiry) < time()) {
    echo json_encode(["success" => false, "message" => "OTP expired"]);
    exit();
}

// Update password and clear OTP
$update = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
$update->bind_param("ss", $hashed, $email);
$update->execute();

echo json_encode(["success" => true, "message" => "Password reset successfully"]);
?>
