<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$dbname = "phpmyadmin";  // Change this
$username = "root";  // Change this
$password = "";  // Change this

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

$farmName = isset($data['name']) ? trim($data['name']) : null;
$farmBoundary = isset($data['boundary']) ? json_encode($data['boundary']) : null;

if (!$farmName || !$farmBoundary) {
    echo json_encode(["success" => false, "error" => "Farm name or boundary is missing"]);
    exit;
}

// Insert into database
$sql = "INSERT INTO farms (name, boundary) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $farmName, $farmBoundary);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Farm saved successfully!"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>