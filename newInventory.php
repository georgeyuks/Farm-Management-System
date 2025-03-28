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
    $type_name = $_POST["type_name"];
    $variety = $_POST["variety"];
    $sku = $_POST["sku"];
    $electronic_id = $_POST["electronic_id"];
    $inventory_unit = $_POST["inventory_unit"];
    $value_per_unit = $_POST["value_per_unit"];
    $kg_per_unit = $_POST["kg_per_unit"];
    $track_lots = isset($_POST["track_lots"]) ? 1 : 0;
    $alert_threshold = $_POST["alert_threshold"];
    $alert_email = $_POST["alert_email"];
    $description = $_POST["description"];

    $sql = "INSERT INTO inventory_types (type_name, variety, sku, electronic_id, inventory_unit, value_per_unit, kg_per_unit, track_lots, alert_threshold, alert_email, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdidiis", $type_name, $variety, $sku, $electronic_id, $inventory_unit, $value_per_unit, $kg_per_unit, $track_lots, $alert_threshold, $alert_email, $description);

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
    <title>New Inventory Type</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 20px; }
        .step { display: flex; align-items: center; margin-bottom: 20px; }
        .step-number { width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; border-radius: 50%; background-color: #ddd; margin-right: 10px; }
        .active { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Inventory Type</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Type Name</label>
                <input type="text" class="form-control" name="type_name" placeholder="Seed, vaccine, grain, etc">
            </div>
            <div class="mb-3">
                <label class="form-label">Variety</label>
                <input type="text" class="form-control" name="variety">
            </div>
            <div class="mb-3">
                <label class="form-label">Internal ID / SKU</label>
                <input type="text" class="form-control" name="sku">
            </div>
            <div class="mb-3">
                <label class="form-label">Electronic ID</label>
                <input type="text" class="form-control" name="electronic_id">
            </div>
            <div class="mb-3">
                <label class="form-label">Inventory Unit</label>
                <select class="form-control" name="inventory_unit">
                    <option value="quantity">Quantity</option>
                    <option value="kg">Kilogram</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estimated Value Per Unit</label>
                <input type="text" class="form-control" name="value_per_unit" placeholder="$">
            </div>
            <div class="mb-3">
                <label class="form-label">Estimated Kg Per Unit</label>
                <input type="text" class="form-control" name="kg_per_unit" placeholder="kg">
            </div>
            <div class="mb-3">
                <input type="checkbox" name="track_lots"> Track individual inventory lots
            </div>
            <div class="mb-3">
                <label class="form-label">Alert When Less Than</label>
                <input type="number" class="form-control" name="alert_less_than" placeholder="Units">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Inventory Alerts To</label>
                <input type="email" class="form-control" name="email_alert" value="yegonin17@gmail.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Next</button>
            <a href="#" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>