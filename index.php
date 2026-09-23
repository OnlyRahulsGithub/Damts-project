

<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: default.php");
    exit();
}

$video_dir = 'video/';
$filename = isset($_GET['file']) ? basename($_GET['file']) : '';





$video_dir = 'video/';
$filename = isset($_GET['file']) ? basename($_GET['file']) : '';

// List all .mp4 files in the video directory
$videos = array_filter(
    array_diff(scandir($video_dir), ['.', '..']),
    fn($file) => is_file($video_dir . $file) && preg_match('/\.mp4$/i', $file)
);
natsort($videos);
$videos = array_values($videos);

// Determine which video to show
$main_video = $filename && in_array($filename, $videos) ? $filename : ($videos[0] ?? null);
$video_url = $main_video ? '/video/' . $main_video : null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DashCam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="style.css">
</head>




<body>
 <div class="top-bar">

    <div class="hamburger" id="hamburger" onclick="openSidebar()">
        <div></div><div></div><div></div>
        
    </div>

<div class="pro-cluster">
<canvas id="speedCanvas" width="180" height="140"></canvas>

<canvas id="rpmCanvas" width="180" height="140"></canvas>

<canvas id="fuelCanvas" width="180" height="140"></canvas>

</div>

</div>
  <div id="overlay" onclick="closeSidebar()"></div>

  <div class="container">
    <div class="sidebar" id="sidebar">
     <div class="sidebar-header">
  <h2>DAMTS</h2>
  <button class="close-btn" onclick="closeSidebar()">&times;</button>
</div>
<div class="sidebar-menu">
      <div class="menu-item">
        Device ID:
          <select id="deviceSelect" onchange="changeDevice()" class="input-field">
  <option value="device_002">device_002</option>
</select>
        
      </div>

      <div class="menu-item">
        Date: <input type="date" id="datePicker" value="<?php echo date('Y-m-d'); ?>" class="input-field">
      </div>
      <div class="menu-item">
 <form action="logout.php" method="post" style="margin-top: 10px;">
  <button class="logout-btn" type="submit">Logout</button>
</form>
</div>
    </div>
    </div>


    <div class="main-content">
      <div class="left-column">

        <div class="map-container" id="map"></div>
        <div class="info-container">
         <div class="obd-info" id="obd-info">
  <h3 class="cont heading-center">OBD Data Real-time</h3>

  <table class="table">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Value</th>
      </tr>
    </thead>

    <tbody id="obdBody">
      <tr><td>Speed</td><td>65 km/h</td></tr>
      <tr><td>RPM</td><td>3000</td></tr>
      <tr><td>Fuel Level</td><td>21%</td></tr>
      <tr><td>Engine Temp</td><td>90°C</td></tr>
    </tbody>
  </table>
</div>
     <div class="obd-info">
  <h3 class="heading-center">GNSS Data Real-time</h3>

  <table class="table">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Value</th>
      </tr>
    </thead>

    <tbody>
      <tr><td>Latitude</td><td>28.668</td></tr>
      <tr><td>Longitude</td><td>77.290</td></tr>
      <tr><td>Altitude</td><td>120 m</td></tr>
    </tbody>
  </table>
</div>
        </div>
      </div>

   <div class="right-column">
<div class="video-controls">

  <div>
    <button onclick="setMode('record')" class="mode-btn">▶ Playback</button>
    <button onclick="setMode('live')" class="mode-btn live">🔴 Live</button>
  </div>



</div>

<div class="video-player">
  <video id="video-player" controls autoplay muted></video>
</div>
<div class="obd-info margin-top-30 full-height-box">

  <h3 class="heading-center heading-left section-heading">
    <span>Date</span>
    <span class="ml-85">Time</span>
  </h3>

  <table class="table">
    <thead>
      <tr class="table-header-row">
        <th id="dateValue">12-feb-2026</th>
        <th id="timeValue">13:03</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>Lat/long</td>
        <td id="latlongValue">28.7041N/77.1205E</td>
      </tr>

      <tr>
        <td>Speed</td>
        <td id="speedValue">65 KMPH</td>
      </tr>

      <tr>
        <td>Fuel Level</td>
        <td id="fuelValue">75%</td>
      </tr>
    </tbody>
  </table>

</div>

            </div>
        </div>
    </div>
  </div>
</body>


<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

 <script src="app.js"></script>







</body>
</html>  