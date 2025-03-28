<?php
$apiKey = "01c954c9e4a8315513a55b3e8e18d4ad"; // Replace with your API key
$city = "Kisii"; // Change this to your preferred city
$apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$apiKey}";

// Fetch weather data using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check if data is valid
if ($data && isset($data["list"])) {
    $current = $data["list"][0]; // Get the current weather
    $cityName = $data["city"]["name"];
    $temperature = round($current["main"]["temp"]);
    $condition = $current["weather"][0]["description"];
    $humidity = $current["main"]["humidity"];
    $windSpeed = $current["wind"]["speed"];
    $cloudCover = $current["clouds"]["all"];
    $precip = isset($current["rain"]["3h"]) ? $current["rain"]["3h"] : 0;

    $hourlyForecast = array_slice($data["list"], 0, 6); // Get the next 6 hours
} else {
    $cityName = "Unknown";
    $temperature = "--";
    $condition = "Error fetching weather";
    $humidity = "--";
    $windSpeed = "--";
    $cloudCover = "--";
    $precip = "--";
    $hourlyForecast = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .weather-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .weather-header {
            font-size: 24px;
            font-weight: bold;
        }
        .current-weather {
            margin: 10px 0;
        }
        .hourly-forecast {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding: 10px;
        }
        .hour {
            background: #e3e3e3;
            padding: 10px;
            border-radius: 8px;
            min-width: 120px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="weather-container">
        <div class="weather-header">Weather for <span><?= $cityName; ?></span></div>
        <div class="current-weather">
            <h2><?= $temperature; ?>°C</h2>
            <p><?= ucfirst($condition); ?></p>
            <p><strong>Humidity:</strong> <?= $humidity; ?>%</p>
            <p><strong>Wind:</strong> <?= $windSpeed; ?> m/s</p>
            <p><strong>Cloud Cover:</strong> <?= $cloudCover; ?>%</p>
            <p><strong>Precipitation:</strong> <?= $precip; ?> mm</p>
        </div>
        <h3>Hourly Forecast</h3>
        <div class="hourly-forecast">
            <?php foreach ($hourlyForecast as $hourData): ?>
                <?php
                $time = date("H:00", $hourData["dt"]);
                $temp = round($hourData["main"]["temp"]);
                $cond = ucfirst($hourData["weather"][0]["main"]);
                $hum = $hourData["main"]["humidity"];
                $wind = $hourData["wind"]["speed"];
                $clouds = $hourData["clouds"]["all"];
                $rain = isset($hourData["rain"]["3h"]) ? $hourData["rain"]["3h"] : 0;
                ?>
                <div class="hour">
                    <strong><?= $time; ?></strong><br>
                    🌡 <?= $temp; ?>°C<br>
                    ☁ <?= $cond; ?><br>
                    💧 <?= $hum; ?>% Humidity<br>
                    🌬 <?= $wind; ?> m/s Wind<br>
                    ☁ <?= $clouds; ?>% Clouds<br>
                    🌧 <?= $rain; ?> mm Rain
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
