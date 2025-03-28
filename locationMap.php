<?php
// Database connection
$host = "localhost";
$dbname = "phpmyadmin";  // Change this if needed
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch saved farms
$farmData = [];
$sql = "SELECT * FROM farms";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $farmData[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Mapping</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        #map-container {
            width: 80%;
            margin: auto;
        }
        #map { 
            height: 300px;
            width: 100%;
            border-radius: 10px;
            border: 2px solid #4CAF50;
        }
        #controls {
            margin-bottom: 10px;
        }
        input, button {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Farm Mapping</h2>
    
    <div id="controls">
        <input type="text" id="farm-name" placeholder="Enter farm name">
        <button id="save-farm">Save Farm</button>
    </div>
    
    <div id="map-container">
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        var map = L.map('map').setView([-0.417, 34.738], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            edit: { featureGroup: drawnItems, remove: true },
            draw: { polygon: true, rectangle: true, circle: false, marker: false }
        });
        map.addControl(drawControl);

        var farmData = null;

        // Capture drawn shape
        map.on('draw:created', function (event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);
            farmData = layer.toGeoJSON();
        });

        // Save to Database
        document.getElementById("save-farm").addEventListener("click", function () {
            var farmName = document.getElementById("farm-name").value;
            if (!farmData || farmName.trim() === "") {
                alert("Please enter a farm name and draw an area.");
                return;
            }

            var farmGeoJSON = JSON.stringify(farmData.geometry);
            fetch('save_farm.php', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ name: farmName, boundary: farmGeoJSON })
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Reload to show saved farms
            })
            .catch(error => console.error("Error:", error));
        });

        // Load Saved Farms
        var farms = <?php echo json_encode($farmData); ?>;
        farms.forEach(farm => {
            var geoJsonLayer = L.geoJSON(JSON.parse(farm.boundary));
            geoJsonLayer.bindPopup("<b>" + farm.name + "</b>");
            geoJsonLayer.addTo(map);
        });
    </script>
</body>
</html>
