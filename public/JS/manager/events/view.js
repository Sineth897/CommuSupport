import {getData} from "../../request.js";
import {displayEventcards} from "../../components/eventcard.js";
import {PopUp} from "../../popUp.js";

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let eventCategory = document.getElementById('eventCategory');
let sameCC = document.getElementById('sameCC');

let popUpArrayKeys = ['name', 'organizedBy','date','time','status','description'];
let popUpArrayLabels = ['Event Category', 'Organized By', ["Date",'date'],['Time','time'],'Status',['Event Description','textarea']];

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
    popUpEvent.setHeader('theme',event);
    popUpEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    popUpEvent.showPopUp();
}
