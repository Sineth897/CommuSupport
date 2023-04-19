import {getData,getTextData} from "../../request.js";
import deliveryPopup from "../../popup/deliveryPopup.js";
import flash from "../../flashmessages/flash.js";
import MapRoute from "../../map/map-route.js";

window.initMap = MapRoute.initMap;

function getDeliveryID(btn) {
    while(!btn.id) {
        btn = btn.parentNode;
    }
    return btn;
}

let routeBtns = document.querySelectorAll('a.del-route');
let finishBtns = document.querySelectorAll('a.del-finish');
let reassignBtns = document.querySelectorAll('a.del-reassign');

for(let i=0;i<routeBtns.length;i++) {
    routeBtns[i].addEventListener('click', showRoute);
}

//function to show route
async function showRoute(e) {
    const parent = getDeliveryID(e.target);

    const routeData = await getData('./delivery/route', 'POST', { data: {subdeliveryID: parent.id.split(',')[0]} });

    if(!routeData['status']) {
        flash.showMessage({value: routeData['message'], type: 'error'});
        return;
    }

    const from = {lat:parseFloat(routeData['data']['fromLatitude']), lng:parseFloat(routeData['data']['fromLongitude'])};
    const to = {lat:parseFloat(routeData['data']['toLatitude']), lng:parseFloat(routeData['data']['toLongitude'])};

    // console.log(from, to);

    const popupContainer = deliveryPopup.showRouteDiv(parent.id);

    const map = await MapRoute.showRoute(from, to, popupContainer.querySelector('#map'));

    // distance and duration
    popupContainer.querySelector('#distance').innerHTML = map['routes'][0]['legs'][0]['distance']['text'];
    popupContainer.querySelector('#duration').innerHTML = map['routes'][0]['legs'][0]['duration']['text'];

}

for(let i=0;i<finishBtns.length;i++) {
    finishBtns[i].addEventListener('click', finishDelivery);
}

//function to finish delivery
async function finishDelivery(e) {
    const parent = getDeliveryID(e.target);

    const stringSplit = parent.id.split(",");

    // console.log(parent.id)

    const finishData = await getData('./delivery/finish', 'POST', { data: {subdeliveryID: stringSplit[0], process:stringSplit[1]}});

    console.log(finishData);

    if(!finishData['status']) {
        flash.showMessage({value: finishData['message'], type: 'error'});
        return;
    }

    flash.showMessage({value: finishData['message'], type: 'success'});
    parent.remove();

}

for(let i=0;i<reassignBtns.length;i++) {
    reassignBtns[i].addEventListener('click', reassignDelivery);
}

async function reassignDelivery(e) {
    const parent = getDeliveryID(e.target);

    const stringSplit = parent.id.split(",");

    const reassignData = await getData('./delivery/reassign', 'POST', { data: {subdeliveryID: stringSplit[0]}});

    console.log(reassignData);

    if(!reassignData['status']) {
        flash.showMessage({value: reassignData['message'], type: 'error'});
        return;
    }

    flash.showMessage({value: reassignData['message'], type: 'success'});
    e.target.removeEventListener('click', reassignDelivery);
}
