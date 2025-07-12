<?php
include 'db.php';

$uid = $_GET['uid'];
$token = $_GET['token'];

$stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND token = ? AND token_expiry > NOW()");
$stmt->bind_param("is", $uid, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["valid" => true]);
} else {
    echo json_encode(["valid" => false]);
}
?>
