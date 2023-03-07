let map, marker;

// These values should be the default on the map.
// When creating CC, default should be the CHO coordinates
// When
let current_lat = 6.9271;
let current_long = 79.8712;


function initMap() {
    // Create a map centered on the default location
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 6.9271, lng: 79.8612},
        zoom: 15,
        disableDefaultUI: true
    });

    // Default value is current position.
    document.getElementById('lat').value = current_lat;
    document.getElementById('lng').value = current_long;

    // Add a marker to the map
    marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        draggable: true
    });

    // Update the latitude and longitude values when the marker is moved
    google.maps.event.addListener(marker, 'dragend', function() {
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    });
}