//
// //Initialize map
// class MapMarker {
//     static point1;
//     static point2;
//
//     static initMap() {
//         MapMarker.point1 = new google.maps.LatLng(37.7749, -122.4194);
//         MapMarker.point2 = new google.maps.LatLng(40.7128, -74.0060);
//
//
//         const map = new google.maps.Map(document.getElementById("map"), {
//             center: { lat: 6.9271, lng: 79.8612 },
//             zoom: 8,
//         });
//         const marker = new google.maps.Marker({
//             position: { lat: 6.9271, lng: 79.8612 },
//             map: map,
//             draggable: true,
//         });
//         google.maps.event.addListener(marker, 'dragend', function (event) {
//             document.getElementById('lat').value = this.getPosition().lat();
//             document.getElementById('lng').value = this.getPosition().lng();
//         });
//     }
//
// }
//
// window.initMap = MapMarker.initMap;
//
// //calculate distance between 2 points
// document.getElementById('test').addEventListener('click', () => {
//     const point1 = new google.maps.LatLng(37.7749, -122.4194)
//     const point2 = new google.maps.LatLng(40.7128, -74.0060)
//
//     const service = new google.maps.DistanceMatrixService();
//
//     service.getDistanceMatrix(
//         {
//             origins: [point1],
//             destinations: [point2],
//             travelMode: google.maps.TravelMode.DRIVING,
//             unitSystem: google.maps.UnitSystem.METRIC,
//             avoidHighways: false,
//             avoidTolls: false,
//         },
//         (response, status) => {
//             if (status !== "OK") {
//                 alert("Error was: " + status);
//             } else {
//                 const originList = response.originAddresses;
//                 const destinationList = response.destinationAddresses;
//                 for (let i = 0; i < originList.length; i++) {
//                     const results = response.rows[i].elements;
//                     for (let j = 0; j < results.length; j++) {
//                         const element = results[j];
//                         const distance = element.distance.text;
//                         const duration = element.duration.text;
//                         const from = originList[i];
//                         const to = destinationList[j];
//                         console.log(distance, duration, from, to);
//                     }
//                 }
//             }
//         });
//
// });
//
//
// //show routes
// document.getElementById('showRoute').addEventListener('click', () => {
//     const point1 = new google.maps.LatLng(6.927079, 79.861244)
//     const point2 = new google.maps.LatLng(6.053519, 80.220978)
//
//     const map = new google.maps.Map(document.getElementById("map"), {
//         center: { lat: 6.9271, lng: 79.8612 },
//         zoom: 8,
//     });
//
//     const directionsService = new google.maps.DirectionsService();
//
//     const directionsRenderer = new google.maps.DirectionsRenderer({
//         map: map,
//     });
//
//     directionsService.route(
//         {
//             origin: point1,
//             destination: point2,
//             travelMode: google.maps.TravelMode.DRIVING,
//         },
//         (response, status) => {
//             if (status === "OK") {
//                 directionsRenderer.setDirections(response);
//             } else {
//                 window.alert("Directions request failed due to " + status);
//             }
//         }
//     );
// });
//
//
