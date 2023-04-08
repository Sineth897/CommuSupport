import mapMarker from "../../map/map-marker.js";
import flash from "../../flashmessages/flash.js";

const cityInput = document.getElementById('city');
const mapDiv = document.getElementById('mapDiv');

document.getElementById('setLocation').addEventListener('click', async (e) => {
    if(cityInput.value === '') {
        flash.showMessage({
            type: "error",
            value: "Please enter a city"
        })
    }
    else {
        if(await mapMarker.changeLocationByCity(cityInput.value)) {
            mapDiv.style.display = 'flex';
        }
    }
});

document.getElementById('confirmLocation').addEventListener('click', function() {
    document.getElementById('mapDiv').style.display = 'none';
});

// document.getElementById('confirm').addEventListener('click', (e) => {
//    e.preventDefault();
//
// });

