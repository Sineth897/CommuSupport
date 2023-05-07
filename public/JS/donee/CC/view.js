import Map from '../../map/map.js';
import flash from "../../flashmessages/flash.js";

let map = new Map();

window.initMap = map.initMap;

let ccCards = document.getElementsByClassName('cc-card');

let filterOptions = document.getElementById('filterOptions');
let filterBtn = document.getElementById('filterBtn');
let district = document.getElementById('district');
document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
});

filterBtn.addEventListener('click', function() {
    let districtId = district.value;

    if(districtId === '') {
        for(let i=0; i<ccCards.length; i++) {
            ccCards[i].style.display = 'block';
        }

    }
    else {
        for(let i=0; i<ccCards.length; i++) {
            let cardDistrict = ccCards[i].getElementsByClassName('cho')[0];
            if (cardDistrict.id === districtId) {
                ccCards[i].style.display = 'block';
            } else {
                ccCards[i].style.display = 'none';
            }
        }
    }
   filterOptions.style.display = 'none';

});

flash.showMessage({type:'success',value:"If maps aren't visible, please refresh the page!"});