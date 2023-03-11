
<link rel="stylesheet" href="./public/CSS/flashMessages.css">
<?php

/** @var $request \app\core\Request */


?>

<div id="map" style="height: 400px; width:400px;" onload="initMap()"></div>

<input type="hidden" id="lat" >
<input type="hidden" id="lng" >


<button id="test">test</button>
<button id="showRoute">Show Route</button>

<script type="module" src="./public/JS/test.js"></script>

<script>

    class MapMarker {
        static point1;
        static point2;

        static initMap() {
            MapMarker.point1 = new google.maps.LatLng(37.7749, -122.4194);
            MapMarker.point2 = new google.maps.LatLng(40.7128, -74.0060);


            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 6.9271, lng: 79.8612 },
                zoom: 8,
            });
            const marker = new google.maps.Marker({
                position: { lat: 6.9271, lng: 79.8612 },
                map: map,
                draggable: true,
            });
            google.maps.event.addListener(marker, 'dragend', function (event) {
                document.getElementById('lat').value = this.getPosition().lat();
                document.getElementById('lng').value = this.getPosition().lng();
            });
        }

    }

    window.initMap = MapMarker.initMap;

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async defer></script>

<script type="module">

    import MapRoute from "./public/JS/map/map-route.js";

document.getElementById('test').addEventListener('click', async () => {
    const point1 = {lat:37.7749,lng:-122.4194};
    const point2 = {lat:40.7128,lng:-74.0060};

    console.log(await MapRoute.calculateDistance(point1,point2));

    // const service = new google.maps.DistanceMatrixService();

    // service.getDistanceMatrix(
    //     {
    //         origins: [point1],
    //         destinations: [point2],
    //         travelMode: google.maps.TravelMode.DRIVING,
    //         unitSystem: google.maps.UnitSystem.METRIC,
    //         avoidHighways: false,
    //         avoidTolls: false,
    //     },
    //     (response, status) => {
    //         if (status !== "OK") {
    //             alert("Error was: " + status);
    //         } else {
    //             const originList = response.originAddresses;
    //             const destinationList = response.destinationAddresses;
    //             for (let i = 0; i < originList.length; i++) {
    //                 const results = response.rows[i].elements;
    //                 for (let j = 0; j < results.length; j++) {
    //                     const element = results[j];
    //                     const distance = element.distance.text;
    //                     const duration = element.duration.text;
    //                     const from = originList[i];
    //                     const to = destinationList[j];
    //                     console.log(distance, duration, from, to);
    //                 }
    //             }
    //         }
    //     });

});

document.getElementById('showRoute').addEventListener('click',async () => {
    const point1 = {lat:6.927079,lng:79.861244};
    const point2 = {lat:6.053519,lng:80.220978};

    const result = await MapRoute.showRoute(point1,point2,document.getElementById('map'));

    console.log(result.routes[0].legs[0].distance.text);
});

</script>

