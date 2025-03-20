<?php
header("Content-Type: application/json"); // Return JSON response

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit();
}

// Get form data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$gender = $_POST['gender'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$number = $_POST['number'] ?? '';

// Basic validation
if (empty($firstName) || empty($lastName) || empty($gender) || empty($email) || empty($password) || empty($number)) {
    echo json_encode(["error" => "All fields are required."]);
    exit();
}

// Hash the password before storing it
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Database connection
$db_host = 'lampdb.crqqg684ylb7.eu-west-1.rds.amazonaws.com';
$db_user = 'admin';
$db_pass = '!234RDSADMIN';
$db_name = 'lampdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Check if the table exists, and create it if it doesn't
$tableCheck = $conn->query("SHOW TABLES LIKE 'registration'");
if ($tableCheck->num_rows == 0) {
    // Table doesn't exist, create it
    $createTableQuery = "
        CREATE TABLE registration (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            gender ENUM('m', 'f', 'o') NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            number VARCHAR(15) NOT NULL
        );
    ";
    if (!$conn->query($createTableQuery)) {
        echo json_encode(["error" => "Failed to create table: " . $conn->error]);
        exit();
    }
}

// Prepare and execute query
$stmt = $conn->prepare("INSERT INTO registration (firstName, lastName, gender, email, password, number) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstName, $lastName, $gender, $email, $hashedPassword, $number);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error: " . $stmt->error]);
} else {
    echo json_encode(["success" => "Registration successful!"]);
}

// Close resources
$stmt->close();
$conn->close();
?>
