<?php
// CORS fix
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendConfirmationEmail($to, $uid, $token) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';   // your SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com'; // your email
    $mail->Password = 'your-app-password';    // app password from Google
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $link = "http://localhost:3000/set-password/$uid/$token";
    $mail->setFrom('your-email@gmail.com');
    $mail->addAddress($to);
    $mail->Subject = "Set Your Password";
    $mail->Body = "Click the link to set your password: $link";

    if (!$mail->send()) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }}
    
?>