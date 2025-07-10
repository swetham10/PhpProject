<?php
function sendConfirmationEmail($to, $name, $link) {
  $subject = "Registration Confirmation";
  $message = "
    <html>
    <body>
      <h3>Hello $name,</h3>
      <p>Click <a href='$link'>here</a> to set your password.</p>
    </body>
    </html>
  ";
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: noreply@yourdomain.com' . "\r\n";

  return mail($to, $subject, $message, $headers);
}
