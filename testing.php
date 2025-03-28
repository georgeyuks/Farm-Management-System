<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Locations Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            display: flex;
            height: 100vh;
        }
        
        .map-container {
            flex: 3;
            background-color: #fff;
        }
        
        #map {
            height: 100%;
            width: 100%;
        }
        
        .locations-panel {
            flex: 1;
            padding: 15px;
            background-color: #fff;
            border-left: 1px solid #ddd;
            overflow-y: auto;
        }
        
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .instructions {
            font-size: 14px;
            margin-bottom: 20px;
            color: #666;
        }
        
        .section-title {
            font-weight: bold;
            margin: 15px 0 10px 0;
            color: #444;
        }
        
        .location-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .location-item {
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        
        .location-item:hover {
            background-color: #f9f9f9;
        }
        
        /* Farm-specific area types */
        .area-type-selector {
            margin: 15px 0;
        }
        
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="map-container">
            <div id="map"></div>
        </div>
        
        <div class="locations-panel">
            <div class="header">Locations Map</div>
            
            <div class="instructions">
                Select an area type then trace its outline by clicking on each corner.
            </div>
            
            <div class="section-title">Add Place to Map</div>
            
            <div class="area-type-selector">
                <select id="area-type">
                    <option value="">Select farm area type</option>
                    <option value="field">Crop Field</option>
                    <option value="pasture">Pasture</option>
                    <option value="orchard">Orchard</option>
                    <option value="barn">Barn</option>
                    <option value="silo">Silo</option>
                    <option value="greenhouse">Greenhouse</option>
                    <option value="pond">Pond</option>
                </select>
            </div>
            
            <div class="section-title">Farm Locations</div>
            <ul class="location-list">
                <li class="location-item">North Wheat Field</li>
                <li class="location-item">South Corn Field</li>
                <li class="location-item">Main Barn</li>
                <li class="location-item">Cattle Pasture</li>
                <li class="location-item">Apple Orchard</li>
                <li class="location-item">Storage Silos</li>
                <li class="location-item">Vegetable Greenhouse</li>
                <li class="location-item">Irrigation Pond</li>
                <li class="location-item">Equipment Shed</li>
                <li class="location-item">Farmhouse</li>
                <li class="location-item">Chicken Coop</li>
                <li class="location-item">Herb Garden</li>
                <li class="location-item">Compost Area</li>
                <li class="location-item">Worker Housing</li>
            </ul>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        // Initialize the map centered on a farm location
        const map = L.map('map').setView([51.505, -0.09], 15);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Feature group to store drawn items
        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        
        // Colors for different farm area types
        const areaColors = {
            field: '#A2CD5A',
            pasture: '#8B864E',
            orchard: '#458B00',
            barn: '#8B4513',
            silo: '#696969',
            greenhouse: '#00C5CD',
            pond: '#1E90FF'
        };
        
        // Initialize drawing control
        let drawControl;
        function initDrawControl(areaType) {
            if (drawControl) {
                map.removeControl(drawControl);
            }
            
            drawControl = new L.Control.Draw({
                draw: {
                    polygon: {
                        shapeOptions: {
                            color: areaColors[areaType] || '#0066cc',
                            fillColor: areaColors[areaType] || '#0066cc',
                            fillOpacity: 0.5
                        },
                        allowIntersection: false,
                        drawError: {
                            color: '#ff0000',
                            message: '<strong>Error:</strong> shape edges cannot cross!'
                        },
                        showArea: true
                    },
                    polyline: false,
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems
                }
            });
            map.addControl(drawControl);
        }
        
        // Handle area type selection
        document.getElementById('area-type').addEventListener('change', function(e) {
            const areaType = e.target.value;
            if (areaType) {
                initDrawControl(areaType);
            } else if (drawControl) {
                map.removeControl(drawControl);
            }
        });
        
        // Handle drawing events
        map.on(L.Draw.Event.CREATED, function(e) {
            const layer = e.layer;
            const areaType = document.getElementById('area-type').value;
            
            // Add type information to the layer
            layer.feature = layer.feature || {};
            layer.feature.type = areaType;
            
            // Add to map
            drawnItems.addLayer(layer);
            
            // Add popup with area info
            const area = L.GeometryUtil.geodesicArea(layer.getLatLngs()[0]);
            const areaText = (area / 10000).toFixed(2) + ' hectares';
            
            layer.bindPopup(`<b>${areaType}</b><br>${areaText}`);
        });
        
        // Make location items clickable (zoom to location)
        const locationItems = document.querySelectorAll('.location-item');
        locationItems.forEach(item => {
            item.addEventListener('click', function() {
                // In a real implementation, you would zoom to the actual coordinates
                // For demo purposes, we'll just pan slightly
                map.panBy([-50, 0], {animate: true});
                
                // Highlight the selected item
                locationItems.forEach(i => i.style.fontWeight = 'normal');
                this.style.fontWeight = 'bold';
            });
        });
    </script>
</body>
</html>



<?php
    
    session_start();
    if (!isset($_SESSION['Username'])) {
        header("Location: index.php");
        exit();
    }
    include 'includes/database.php';
    include 'includes/action.php';

    $sql = "SELECT * FROM Employee";
    $res = $databaseObject->connect()->query($sql);

    
?>