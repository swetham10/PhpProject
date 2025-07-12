<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

$stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
$stmt->bind_param("sss", $otp, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $subject = "Your OTP Code";
    $message = "Your OTP is: $otp (valid for 10 minutes)";
    $headers = "From: no-reply@yourdomain.com";
    mail($email, $subject, $message, $headers);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Email not found"]);
}
?>
