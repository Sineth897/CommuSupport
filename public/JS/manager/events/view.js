import {getData} from "../../request.js";
import {displayEventcards} from "../../components/eventcard.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let eventCategory = document.getElementById('eventCategory');
let sameCC = document.getElementById('sameCC');

let popUpArrayKeys = ['organizedBy','date','time','location','status','description'];
let popUpArrayLabels = ['Organized By', ["Date",'date'],['Time','time'], 'Location', 'Status',['Event Description','textarea']];

updateEventCardOnClick();
filterBtn.addEventListener('click', async function() {

    let filterValues = {};

    if (eventCategory.value) {
        filterValues['eventCategoryID'] = eventCategory.value;
    }
    if(sameCC.checked) {
        filterValues['ccID'] = sameCC.value;
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
    popUpEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    popUpEvent.setButtons([{text:'Update',classes:['btn-primary'],value:event['eventID'],func:updateFunc},
        {text:'Delete',classes:['btn-danger'],value:event['eventID'],func:deleteFunc}]);
    popUpEvent.showPopUp();
}

let updateFunc = (e) => {
    let fieldsToUpdate = ['date', 'time', 'location', 'description'];
    let fields = {};
    for (let i = 0; i < fieldsToUpdate.length; i++) {
        fields[fieldsToUpdate[i]] = document.getElementById(fieldsToUpdate[i]);
    }
    if(e.target.innerHTML === 'Update') {
        e.target.innerHTML = 'Confirm';
        for (let i = 0; i < fieldsToUpdate.length; i++) {
            fields[fieldsToUpdate[i]].removeAttribute('disabled');
        }
    } else {
        e.target.innerHTML = 'Update';
        for (let i = 0; i < fieldsToUpdate.length; i++) {
            fields[fieldsToUpdate[i]].setAttribute('disabled','');
        }
        let updateValues = {};
        for (let i = 0; i < fieldsToUpdate.length; i++) {
            updateValues[fieldsToUpdate[i]] = fields[fieldsToUpdate[i]].value;
        }
        updateValues['eventID'] = e.target.value;
        console.log(updateValues);
    }
}

let deleteFunc = (e) => {
    console.log(e.target.value,'delete');
}