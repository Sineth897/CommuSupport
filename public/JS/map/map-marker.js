import flash from "../flashmessages/flash.js";


class MapMarker
{
    static map;
    static marker;


// These values should be the default on the map.
// When creating CC, default should be the CHO coordinates
// When
    static current_lat = parseFloat(document.getElementById('lat').value);
    static current_long = parseFloat(document.getElementById('lng').value);


    static initMap = () => {
        // Create a map centered on the default location
        MapMarker.map = new google.maps.Map(document.getElementById('map') , {
            center: {lat:MapMarker.current_lat, lng: MapMarker.current_long},
            zoom: 15,
            disableDefaultUI: true
        });

        // Default value is current position.
        document.getElementById('lat').value = MapMarker.current_lat;
        document.getElementById('lng').value = MapMarker.current_long;

        // Add a marker to the map
        MapMarker.marker = new google.maps.Marker({
            position: MapMarker.map.getCenter(),
            map: MapMarker.map,
            draggable: true
        });

        // Update the latitude and longitude values when the marker is moved
        google.maps.event.addListener(MapMarker.marker, 'dragend', async function () {
            await MapMarker.updateMap();
        });

        google.maps.event.addListener(MapMarker.map, 'click', async function (event) {
            MapMarker.marker.setPosition(event.latLng);
            await MapMarker.updateMap();
        });
    }

    static async updateMap() {
        MapMarker.current_lat = await MapMarker.marker.getPosition().lat();
        MapMarker.current_long = await MapMarker.marker.getPosition().lng();
        document.getElementById('lat').value = MapMarker.current_lat;
        document.getElementById('lng').value = MapMarker.current_long;
    }

    changeLocation(lat, lng) {
        MapMarker.marker.setPosition(new google.maps.LatLng(lat, lng));
        MapMarker.map.setCenter(new google.maps.LatLng(lat, lng));
        MapMarker.updateMap();
    }

    static async changeLocationByCity(city) {
        const geocoder = new google.maps.Geocoder();
        await geocoder.geocode({address: city}, (results, status) => {
            if(status === 'OK') {
                MapMarker.map.setCenter(results[0].geometry.location);
                MapMarker.marker.setPosition(results[0].geometry.location);
                MapMarker.updateMap();
                document.getElementById('mapDiv').style.display = 'flex';
                return true;
            }
            else {
                flash.showMessage({
                    type: "error",
                    value: "Could not find location for the entered city"
                });
                return false;
            }
        });
    }

}

export default MapMarker;
