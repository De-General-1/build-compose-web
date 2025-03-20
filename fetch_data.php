<?php
header("Content-Type: application/json"); // Return JSON response

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

// Check if the table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'registration'");
if ($tableCheck->num_rows == 0) {
    echo json_encode(["message" => "No data found."]);
    exit();
}

// Fetch data from the 'registration' table
$sql = "SELECT firstName, lastName, gender, email, number FROM registration";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo json_encode(["message" => "No data found."]);
    exit();
}

// Return data as JSON
echo json_encode($data);

$conn->close();
?>
