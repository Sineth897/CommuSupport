import {getData} from "../../request.js";
import {displayEventcards} from "../../components/eventcard.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let eventCategory = document.getElementById('eventCategory');

let popUpArrayKeys = ['organizedBy','date','time','location','description'];
let popUpArrayLabels = ['Organized By', ["Date",'date'],['Time','time'], 'Location',['Event Description','textarea']];

updateEventCardOnClick();
filterBtn.addEventListener('click', async function() {

    let filterValues = {};

    if (eventCategory.value) {
        filterValues['eventCategoryID'] = eventCategory.value;
    }

    let array = await getData('./events/filter', 'POST', filterValues);

    displayEventcards(eventsDiv,array);
    updateEventCardOnClick();

});

function updateEventCardOnClick() {
    let eventCards = document.getElementsByClassName('eventCard');
    for(let i = 0; i < eventCards.length; i++) {
        eventCards[i].addEventListener('click', (e) => showPopUp(e));
    }
}

let popUpEvent = new PopUp();

async function showPopUp(e) {
    let eventCard = e.target;
    while(eventCard.className !== 'eventCard') {
        eventCard = eventCard.parentNode;
    }
    let event = await getData('./events/popup', 'POST', {"event.eventID": eventCard.id});
    let eventIcons = event['icons'];
    event = event['event'];

    popUpEvent.clearPopUp();
    popUpEvent.setHeader('theme',event,'name');

    popUpEvent.startPopUpInfo();
    popUpEvent.showStatus(event['status']);
    popUpEvent.showParticipants(event['participationCount']);
    popUpEvent.endPopUpInfo();

    popUpEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    if(event['status'] !== 'Cancelled') {
        popUpEvent.setButtons([{text:'Update',classes:['btn-primary'],value:event['eventID'],func:updateFunc},
            {text:'Cancel Event',classes:['btn-danger'],value:event['eventID'],func:cancelFunc}]);
    }
    popUpEvent.showPopUp();
}

let popUpFunctions = new PopUpFunctions();

let updateFunc = async (e) => {
    let fieldsToUpdate = ['date', 'time', 'location', 'description'];

    if(e.target.innerHTML === 'Update') {
        popUpFunctions.changeToInput(e.target,fieldsToUpdate);
        popUpFunctions.hideAllElementsWithin(e.target.parentNode);
        e.target.style.display = 'block';
        e.target.nextElementSibling.style.display = 'block';
    }
    else {
        let updateValues = popUpFunctions.getUpdatedValues(e.target,fieldsToUpdate);
        updateValues['eventID'] = e.target.value;
        let result = await getData('./events/update', 'POST', {do:'update',data:updateValues});
        if(result['status']) {
            console.log('updated');
        } else {
            console.log(result);
        }
        popUpEvent.hidePopUp();
        document.getElementById(e.target.value).click();
    }
}

let cancelFunc = async (e) => {
    if(e.target.innerText === 'Cancel Event') {
        e.target.innerText = 'Confirm';
        popUpFunctions.hideAllElementsWithin(e.target.parentNode);
        e.target.style.display = 'block';
        e.target.nextElementSibling.style.display = 'block';
    }
    else {
        let result = await getData('./events/update', 'POST', {do:'cancel',data:e.target.value});
        if(result['status']) {
            console.log('Success');
        } else {
            console.log(result);
        }
        popUpEvent.hidePopUp();
        document.getElementById('filterBtn').click();
        e.target.innerText = 'Cancel Event';
    }
}