<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Parse JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Extract fields
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$dob = !empty($data['dob']) ? $data['dob'] : null;
$languages = isset($data['languages']) ? implode(',', $data['languages']) : '';
$country = $data['country'] ?? '';
$state = $data['state'] ?? '';
$city = $data['city'] ?? '';

// Validate required fields
if (!$firstName || !$email || !$dob) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit();
}

// Check for duplicate email
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered.']);
    exit();
}

// Generate token and link
$uid = uniqid();
$token = bin2hex(random_bytes(16));
$tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
$confirmationLink = "http://localhost:3000/set-password/$uid/$token";

// Insert into DB
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, dob, languages, country, state, city, uid, token, token_expiry)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param(
    "ssssssssssss",
    $firstName, $lastName, $username, $email, $dob,
    $languages, $country, $state, $city, $uid, $token, $tokenExpiry
);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'DB insert failed: ' . $stmt->error]);
    exit();
}

// Send confirmation email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mswetham003@gmail.com';        // ✅ Replace with your Gmail
    $mail->Password   = 'rngjoiwonrywgtne';           // ✅ Not real Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('your_email@gmail.com', 'Your App');
    $mail->addAddress($email, $firstName);

    $mail->isHTML(true);
    $mail->Subject = 'Confirm Your Registration';
    $mail->Body = "
        <p>Hi $firstName,</p>
        <p>Thank you for registering!</p>
        <p>Click the link below to set your password:</p>
        <p><a href='$confirmationLink'>$confirmationLink</a></p>
        <br><p>Regards,<br>Your App Team</p>
    ";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Registration successful. Please check your email to set a password.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Email sending failed: ' . $mail->ErrorInfo]);
}
?>
