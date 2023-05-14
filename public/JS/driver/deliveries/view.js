import {getData,getTextData} from "../../request.js";
import deliveryPopup from "../../popup/deliveryPopup.js";
import flash from "../../flashmessages/flash.js";
import MapRoute from "../../map/map-route.js";
import driverDeliveryCard from "../../components/driverDeliveryCard.js";
import togglePages from "../../togglePages.js";

const toggle = new togglePages([{btnID:'deliveries',pageId:'assignedDeliveries'}]);

window.initMap = MapRoute.initMap;

function getDeliveryID(btn) {
    while(!btn.id) {
        btn = btn.parentNode;
    }
    return btn;
}

// assign event listeners to relevant buttons
function assignEventlistenersToRelevantButtons() {

    let routeBtns = document.querySelectorAll('a.del-route');
    let finishBtns = document.querySelectorAll('a.del-finish');
    let reassignBtns = document.querySelectorAll('a.del-reassign');

    for(let i=0;i<routeBtns.length;i++) {
        routeBtns[i].addEventListener('click', showRoute);
    }

    for(let i=0;i<finishBtns.length;i++) {
        finishBtns[i].addEventListener('click', finishDelivery);
    }


    for(let i=0;i<reassignBtns.length;i++) {
        reassignBtns[i].addEventListener('click', reassignDelivery);
    }

}

assignEventlistenersToRelevantButtons();

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

//function to finish delivery
async function finishDelivery(e) {

    const parent = getDeliveryID(e.target);

    const stringSplit = parent.id.split(",");

    // console.log(parent.id)

    const finishData = await getData('./delivery/finish', 'POST', { data: {subdeliveryID: stringSplit[0], process:stringSplit[1]}});
    // const finishData = await getTextData('./delivery/finish', 'POST', { data: {subdeliveryID: stringSplit[0], process:stringSplit[1]}});
    // console.log(finishData);

    if(!finishData['status']) {
        flash.showMessage({value: finishData['message'], type: 'error'});
        return;
    }

    flash.showMessage({value: finishData['message'], type: 'success'});
    parent.remove();

    filterBtn.click();

}

async function reassignDelivery(e) {
    const parent = getDeliveryID(e.target);

    const stringSplit = parent.id.split(",");

    const reassignData = await getData('./delivery/reassign', 'POST', { data: {subdeliveryID: stringSplit[0],do: e.target.innerHTML}});
    // const reassignData = await getTextData('./delivery/reassign', 'POST', { data: {subdeliveryID: stringSplit[0],do: e.target.innerHTML}});

    // console.log(reassignData);

    if(!reassignData['status']) {
        flash.showMessage({value: reassignData['message'], type: 'error'});
        return;
    }

    flash.showMessage({value: reassignData['message'], type: 'success'});
    e.target.innerHTML = reassignData['innerHTML'];
}

let filterOptions = document.getElementById('filterOptions');
let sortOptions = document.getElementById('sortOptions');

document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
    sortOptions.style.display = 'none';
});

document.getElementById('sort').addEventListener('click', function(e) {
    if(sortOptions.style.display === 'block') {
        sortOptions.style.display = 'none';
    } else {
        sortOptions.style.display = 'block';
    }
    filterOptions.style.display = 'none';
});

filterOptions.addEventListener('click', function(e) {
    e.stopPropagation();
});

sortOptions.addEventListener('click', function(e) {
    e.stopPropagation();
});

const assignedDeliveriesDiv = document.getElementById('assignedDeliveries');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const categoryFilter = document.getElementById('filterCategory');

const sortCreatedDate = document.getElementById('sortCreatedDate');

filterBtn.addEventListener('click', async function(e) {

    let filters = {};

    if(categoryFilter.value) {
        filters['category'] = categoryFilter.value;
    }

    let sort = {DESC:[]};

    if(sortCreatedDate.checked) {
        sort['DESC'].push('s.createdDate');
    }

    const result = await getData('./deliveries/filter','post',{filters:filters,sort:sort});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type: "error", value: result['message']});
        return;
    }

    toggle.removeNoData();

    const deliveries = result['deliveries'];
    const destinations = result['destinations'];
    const subcategories = result['subcategories'];

    deliveries.forEach(delivery => {
        delivery['startAddress'] = destinations[delivery['start']];
        delivery['endAddress'] = destinations[delivery['end']];
        delivery['item'] = subcategories[delivery['item']];
    });

    driverDeliveryCard.showDeliveries(assignedDeliveriesDiv,deliveries);

    toggle.checkNoData();

    assignEventlistenersToRelevantButtons();

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});