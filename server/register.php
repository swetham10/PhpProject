<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!$data) {
  echo json_encode(["message" => "No input received"]);
  exit;
}

// Extract and sanitize
$firstName = $data->firstName ?? '';
$email = $data->email ?? '';
$username = $data->username ?? '';
$link = "http://localhost/set-password.php?user=" . urlencode($username);

// Send email
require 'mailer.php';
if (sendConfirmationEmail($email, $firstName, $link)) {
  echo json_encode(["message" => "Registration successful. Check your email."]);
} else {
  echo json_encode(["message" => "Email failed to send"]);
}
