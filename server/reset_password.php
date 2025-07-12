<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
$otp = $data['otp'];
$password = $data['password'];
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if ($user['otp'] === $otp && $user['otp_expiry'] > date("Y-m-d H:i:s")) {
        $stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}
?>
