import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";
import { getData } from "../../request.js";
import deliveryPopUp from "../../popup/deliveryPopUp.js";
import MapRoute from "../../map/map-route.js";

window.initMap = MapRoute.initMap;

let toggle = new togglePages([{btnId:'pending',pageId:'pendingDeliveries'},{btnId:'completed',pageId:'completedDeliveries'}]);

let deliveryCards = document.querySelectorAll('.delivery-card');
let assignBtns = {};

for(let i=0;i<deliveryCards.length;i++) {
    let assignBtn = deliveryCards[i].querySelector('.view-btn');
    assignBtn.addEventListener('click', showDeliveryPopUp)
    assignBtns[assignBtn.id] = deliveryCards[i];
}

const deliverypopup = new deliveryPopUp();

async  function showDeliveryPopUp(e) {

    const data = {
        deliveryType: e.target.value, //to know whether it is a accepted request,direct donation or ccdonation
        deliveryID: e.target.id, //subdeliveryID
        related: assignBtns[e.target.id].id //ID of the related process
    };

    const result = await getData('./delivery/popup', 'POST', { data: data });

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({value: result['message'], type: 'error'});
        return;
    }

    let deliveryData = result['data']
    let drivers = result['drivers'];
    const addresses = result['addresses'];

    deliveryData['startAddress'] = addresses[deliveryData['start']];
    deliveryData['endAddress'] = addresses[deliveryData['end']];

    const popup = await deliverypopup.showDeliveryPopUp(deliveryData,e.target.value);

    const from = {lat: parseFloat(deliveryData['fromLatitude']), lng: parseFloat(deliveryData['fromLongitude'])};
    const to = {lat: parseFloat(deliveryData['toLatitude']), lng: parseFloat(deliveryData['toLongitude'])};

    // const map = await MapRoute.showRoute(from, to, popup.querySelector('#map'));
    //
    // const distance = parseFloat(map['routes'][0]['legs'][0]['distance']['text']);
    //
    // popup.querySelector('#distance').innerHTML = map['routes'][0]['legs'][0]['distance']['text'];

    if(deliveryData['deliveryStatus'] !== 'Not assigned') {
        let reassignDriverDiv = popup.querySelector('#reassignedDriver')
        reassignDriverDiv.style.display = 'block';
        const driverIndex = drivers.findIndex(driver => driver['driverID'] === deliveryData['driverID']);
        reassignDriverDiv.querySelector('h2').innerHTML = drivers[driverIndex]['name'];
        drivers.splice(driverIndex, 1);
    }

    if(!drivers.length > 0) {
        popup.querySelector('#driverSelectionError').innerHTML = 'No drivers available';
        return;
    }

    const distance = 5.9;

    drivers = drivers.map(driver => { driver['score'] = driver['active'] + (driver['preference'] === '< 10km' ? distance >= 10.0 : distance < 10.0); return driver; });

    drivers = sortDrivers(drivers, drivers.length);

    const driverScroller = popup.querySelector('#driverScroller');

    drivers.map(driver => {
       driverScroller.appendChild(deliveryPopUp.getDriverCard(driver))
    });

    popup.querySelector('.driver-assign-btn').addEventListener('click', assignDriver);

}


//using bubble sort
function sortDrivers(drivers, driversCount) {
    let swapped = false;
    for(let i=0;i<driversCount-1;i++) {
        swapped = false;
        for(let j=0;j<driversCount-1;j++) {
            if(drivers[j]['score'] > drivers[j+1]['score']) {
                let temp = drivers[j];
                drivers[j] = drivers[j+1];
                drivers[j+1] = temp;
                swapped = true;
            }
        }
        if(!swapped) {
            break;
        }
    }
    // console.log(drivers);
    return drivers;
}

async function assignDriver(e) {
    const ids = e.target.id.split(',');
    const selectedDriverDiv = document.getElementById('driverScroller').querySelector('div.selected');

    if(!selectedDriverDiv) {
        // document.getElementById( 'driverSelectionError').innerHTML = 'Please select a driver';
        flash.showMessage({value: 'Please select a driver', type: 'error'});
        return;
    }

    let data = {
        subdeliveryID: ids[0],
        processID: ids[1],
        related: ids[2],
        driverID: selectedDriverDiv.id
    }

    const result = await getData('./delivery/assign', 'POST', { data });

    if(!result['status']) {
        flash.showMessage({value: result['message'], type: 'error'});
        return;
    }

    assignBtns[data['subdeliveryID']].remove();
    deliveryPopUp.closePopUp();

    console.log(result);
}