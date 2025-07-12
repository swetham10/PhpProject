<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get POST data from JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Extract and validate fields
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$dob = $data['dob'] ?? '';
$languages = implode(',', $data['languages'] ?? []);
$country = $data['country'] ?? '';
$state = $data['state'] ?? '';
$city = $data['city'] ?? '';

// TODO: Save user data into database here (optional)

// Generate a unique token and UID (for password setup link)
$uid = uniqid();
$token = bin2hex(random_bytes(16));

// Save uid/token into DB if needed
// Example link (adjust frontend URL accordingly)
$confirmationLink = "http://localhost:3000/set-password/$uid/$token";

// Send confirmation email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ashakohandan2003@gmail.com';        // ✅ Replace with your Gmail
    $mail->Password   = 'kothandan123';           // ✅ Replace with Gmail app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('your_email@gmail.com', 'Your App');
    $mail->addAddress($email, $firstName); // ✅ This is now defined

    $mail->isHTML(true);
    $mail->Subject = 'Confirm Your Registration';
    $mail->Body    = "
        <p>Hi $firstName,</p>
        <p>Thanks for registering!</p>
        <p>Click this link to set your password:</p>
        <p><a href='$confirmationLink'>$confirmationLink</a></p>
        <br><p>Regards,<br>Your Team</p>
    ";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Registration successful. Check your email.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Email sending failed: ' . $mail->ErrorInfo]);
}
