<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
include 'includes/database.php';
include 'includes/action.php';
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? '';
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $modelYear = $_POST['model_year'] ?? '';
    $plateNumber = $_POST['plate_number'] ?? '';
    $serialNumber = $_POST['serial_number'] ?? '';
    $engine = $_POST['engine'] ?? '';
    $transmission = $_POST['transmission'] ?? '';
    $trackUsage = $_POST['track_usage'] ?? '';
    $currentUsage = $_POST['current_usage'] ?? '';
    $serviceReminder = $_POST['service_reminder'] ?? '';
    $emailAlerts = $_POST['email_alerts'] ?? '';
    $usageCost = $_POST['usage_cost'] ?? '';
    $serviceManual = $_POST['service_manual'] ?? '';
    $leasedPurchased = $_POST['leased_purchased'] ?? '';
    $dateAcquired = $_POST['date_acquired'] ?? '';
    $purchasePrice = $_POST['purchase_price'] ?? '';
    $insured = isset($_POST['insured']) ? 'Yes' : 'No';
    $estimatedValue = $_POST['estimated_value'] ?? '';
    $description = $_POST['description'] ?? '';
    
    echo "<h3>Form Submitted Successfully</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: auto;
        }
        label, input, select, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="radio"], input[type="checkbox"] {
            display: inline;
            width: auto;
        }
    </style>
</head>
<body>
    <h2>New Equipment</h2>
    <form method="POST">
        <label>Name <input type="text" name="name"></label>
        <label>Type <input type="text" name="type" placeholder="Tractor, Harvester, etc"></label>
        <label>Status 
            <select name="status">
                <option value="">Select Status</option>
                <option value="At Dealer">At Dealer</option>
                <option value="Decommissioned">Decommissioned</option>
                <option value="Do Not Start">Do Not Start</option>
                <option value="Do Not Use">Do Not Use</option>
                <option value="In Use">In Use</option>
                <option value="Loaned Out">Loaned Out</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Out of Service">Out of Service</option>
                <option value="Pending Validation">Pending Validation</option>
                <option value="Repair">Repair</option>
                <option value="Sold">Sold</option>
                <option value="Tagged Out">Tagged Out</option>
                <option value="Under Review">Under Review</option>
            </select>
        </label>
        <label>Brand/Model <input type="text" name="brand" placeholder="John Deere, Kubato, etc"> <input type="text" name="model" placeholder="Model (eg: 1023E)"></label>
        <label>Model Year <input type="text" name="model_year"></label>
        <label>ID/Plate Number <input type="text" name="plate_number"></label>
        <label>Serial Number <input type="text" name="serial_number"></label>
        <label>Engine/Transmission <input type="text" name="engine" placeholder="Engine (eg: 2.9L 3-cyl diesel)"> <input type="text" name="transmission" placeholder="Transmission (eg: Collar shift Hi-Lo)"></label>
        <label>Track Usage (Miles/Hours) <select name="track_usage"><option value="Hours">Hours</option><option value="Miles">Miles</option></select></label>
        <label>Current (Miles/Hours) <input type="number" name="current_usage" value="0.0"></label>
        <label>Send Service Reminder Every <input type="text" name="service_reminder" placeholder="(Miles/Hours)"></label>
        <label>Email Alerts To <input type="email" name="email_alerts"></label>
        <label>Estimated Usage Cost <input type="text" name="usage_cost" placeholder="$"> per (miles/hours)</label>
        <label>Link To Service Manual <input type="text" name="service_manual" placeholder="http:// Paste link to manual here"></label>
        <label>Leased Or Purchased <input type="radio" name="leased_purchased" value="Leased"> Leased <input type="radio" name="leased_purchased" value="Purchased"> Purchased</label>
        <label>Date Acquired <input type="date" name="date_acquired"></label>
        <label>Purchase Price <input type="text" name="purchase_price" placeholder="$ 0.00"> <input type="checkbox" name="insured"> Equipment is Insured</label>
        <label>Estimated Value <input type="text" name="estimated_value" placeholder="$ 0.00"></label>
        <label>Description <textarea name="description"></textarea></label>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
