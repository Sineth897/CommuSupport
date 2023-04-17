import {getData} from "../../request.js";
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


async function showRoute(e) {
    const parent = getDeliveryID(e.target);

    const routeData = await getData('./delivery/route', 'POST', { data: {subdeliveryID: parent.id} });

    if(!routeData['status']) {
        flash.showMessage({value: routeData['message'], type: 'error'});
        return;
    }

    const from = {lat:parseFloat(routeData['data']['fromLatitude']), lng:parseFloat(routeData['data']['fromLongitude'])};
    const to = {lat:parseFloat(routeData['data']['toLatitude']), lng:parseFloat(routeData['data']['toLongitude'])};

    // console.log(from, to);

    const popupContainer = deliveryPopup.showRouteDiv(parent.id);

    const map = await MapRoute.showRoute(from, to, popupContainer.querySelector('#map'));

    popupContainer.querySelector('#distance').innerHTML = map['routes'][0]['legs'][0]['distance']['text'];
    popupContainer.querySelector('#duration').innerHTML = map['routes'][0]['legs'][0]['duration']['text'];

}
}