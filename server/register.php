<?php
// CORS support
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Read and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

// Extract fields from frontend
$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';
$dob = $data['dob'] ?? '';
$languages = isset($data['languages']) ? implode(',', $data['languages']) : '';
$country = $data['country'] ?? '';
$state = $data['state'] ?? '';
$city = $data['city'] ?? '';

// Basic validation
if (empty($firstName) || empty($username) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Generate confirmation token
$token = bin2hex(random_bytes(16));
$confirmationLink = "http://localhost/set_password.php?token=$token";

// Connect to MySQL
$host = "localhost";
$dbUser = "root";
$dbPass = "1234";
$dbName = "testdb"; // change this to your DB name

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Create users table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(100),
    email VARCHAR(255),
    dob DATE,
    languages TEXT,
    country VARCHAR(100),
    state VARCHAR(100),
    city VARCHAR(100),
    token VARCHAR(64),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Prepare and insert data
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, dob, languages, country, state, city, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $firstName, $lastName, $username, $email, $dob, $languages, $country, $state, $city, $token);

if ($stmt->execute()) {
    // Send confirmation email
    $subject = "Confirm Your Registration";
    $message = "Hi $firstName,\n\nThanks for registering!\n\nClick this link to set your password:\n$confirmationLink\n\nRegards,\nYour Team";
    $headers = "From: noreply@yourdomain.com\r\n";

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Registration successful. Check your email.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email sending failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database insert failed.']);
}

$stmt->close();
$conn->close();
?>
