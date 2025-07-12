<?php
include 'db.php';
include 'mailer.php';

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $email = $user['email'];
    $token = bin2hex(random_bytes(16));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $conn->prepare("UPDATE users SET token = ?, token_expiry = ? WHERE id = ?");
    $stmt->bind_param("ssi", $token, $expiry, $uid);
    $stmt->execute();

    sendConfirmationEmail($email, $uid, $token);
    echo json_encode(["message" => "Email sent"]);
} else {
    echo json_encode(["message" => "User not found"]);
}
?>
