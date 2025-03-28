<?php
// config.php - Database Connection
$host = "localhost";
$dbname = "phpmyadmin";
$username = "root";  // Change if necessary
$password = "";  // Change if necessary

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $internal_id = $_POST["internal_id"];
    $track_capacity = $_POST["track_capacity"];
    $description = $_POST["description"];

    $sql = "INSERT INTO warehouses (name, internal_id, track_capacity, description) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $internal_id, $track_capacity, $description);

    if ($stmt->execute()) {
        // Redirect to warehouse.php after success
        header("Location: warehouse.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Warehouse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>New Warehouse</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label>Internal ID</label>
                <input type="text" class="form-control" name="internal_id">
            </div>
            <div class="mb-3">
                <label>Track Capacity</label><br>
                <input type="radio" name="track_capacity" value="bins" checked> In separate bins
                <input type="radio" name="track_capacity" value="location"> Only in this location
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="warehouses.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
