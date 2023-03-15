<!DOCTYPE html>
<html>
<head>
    <title>Google Maps Location Form</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVIH58WRiJgZqBQJNRbPRhrwWRnuNLOo0&callback=initMap"
            async defer></script>
    <script src="map-marker.js"></script>
</head>
<body onload="initMap()">
<form method="post" action="map-input.php">
<!-- hidden input fields to save the points -->
    <input type="hidden" id="lat" name="lat" />
    <input type="hidden" id="lng" name="lng" />
    <div id="map" style="height: 400px; width:400px;"></div>
    <input type="submit" value="Submit" />
</form>


<?php
// Can edit php code to get the latitude and logtitude
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lng = $_POST['lng'];
    $lat = $_POST['lat'];

    echo "<p>Long: $lng</p>";
echo "<p>Lat: $lat</p>";
}

?>


</body>

</html>

