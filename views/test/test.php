<link rel="stylesheet" href="./public/CSS/flashMessages.css">
<?php

/** @var $request \app\core\Request */


?>

<div id="map" style="height: 400px; width:400px;" onload="initMap()"></div>

<input type="hidden" id="lat" >
<input type="hidden" id="lng" >


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl1ekIlhUhjhwMjrCqiZ5-fOWaxRIAKos&callback=initMap" async defer></script>
<script type="module" src="./public/JS/map/map-marker.js"></script>

