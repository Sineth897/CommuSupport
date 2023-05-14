import { getData, getTextData } from "../../request.js";
import flash from "../../flashmessages/flash.js";
import {PopUp} from "../../popup/popUp.js";
import driverDeliveryCard from "../../components/driverDeliveryCard.js";

// query completed delivery cards and add event listeners
function assignViewDeliveryToDeliveryCards() {

    const deliveryCards = document.querySelectorAll(".view-completed-delivery");

    for(let i = 0, length = deliveryCards.length; i < length; i++) {
        deliveryCards[i].addEventListener("click", showPopup);
    }

}

// first function call
assignViewDeliveryToDeliveryCards();

function getParentWithID(element) {

        while(element.id === "") {
            element = element.parentElement;
        }

        return element;
}

const popUp = new PopUp();

// show popup function
async function showPopup(e) {

    const parent = getParentWithID(e.target);

    const result = await getData('./popup','post', {subdeliveryID: parent.id.split(",")[0]});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type: "error", value: result['message']});
        return;
    }

    let subdelivery = result['subdeliveryDetails'];
    const destinations = result['destinationAddresses'];

    subdelivery['startAddress'] = destinations[subdelivery['start']];
    subdelivery['endAddress'] = destinations[subdelivery['end']];
    subdelivery['distance'] = subdelivery['distance'] + " km";

    // structure popup
    popUp.clearPopUp();
    popUp.setHeader("View Delivery");

    popUp.startSplitDiv();
    popUp.setBody(subdelivery,['createdDate','startAddress','distance'],['Delivery Created Date',['Pickup Address','textarea'],'Delivery Distance']);
    popUp.setBody(subdelivery,['completedDate','endAddress'],['Delivery Completed Date', ['Drop Off Address','textarea']]);
    popUp.endSplitDiv();

    popUp.showPopUp();

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

const completedDeliveriesDiv = document.getElementById('completedDeliveries');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const categoryFilter = document.getElementById('filterCategory');

const sortDistance = document.getElementById('sortDistance');
const sortCreatedDate = document.getElementById('sortCreatedDate');
const sortCompletedDate = document.getElementById('sortCompletedDate');

filterBtn.addEventListener('click', async function(e) {

    let filters = {};

    if(categoryFilter.value) {
        filters['category'] = categoryFilter.value;
    }

    let sort = {DESC:[]};

    if(sortDistance.checked) {
        sort['DESC'].push('s.distance');
    }

    if(sortCreatedDate.checked) {
        sort['DESC'].push('s.createdDate');
    }

    if(sortCompletedDate.checked) {
        sort['DESC'].push('s.completedDate');
    }

    const result = await getData('./completed/filter','post',{filters:filters,sort:sort});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type: "error", value: result['message']});
        return;
    }

    const deliveries = result['deliveries'];
    const destinations = result['destinations'];
    const subcategories = result['subcategories'];

    deliveries.forEach(delivery => {
        delivery['startAddress'] = destinations[delivery['start']];
        delivery['endAddress'] = destinations[delivery['end']];
        delivery['item'] = subcategories[delivery['item']];
    });

    driverDeliveryCard.showDeliveries(completedDeliveriesDiv,deliveries);

    assignViewDeliveryToDeliveryCards();

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});