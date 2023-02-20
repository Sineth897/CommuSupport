import {getData} from "../../request.js";
import {displayEventcards} from "../../components/eventcard.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";

let eventCards = document.getElementsByClassName('event-card');

prepareEventCards();

function prepareEventCards() {
    for(let i=0; i<eventCards.length; i++) {
        eventCards[i].addEventListener('click', (e) => showPopUp(e));
    }
}

let popupEvent = new PopUp();
let popUpArrayKeys = ['organizedBy','date','time','location','description'];
let popUpArrayLabels = ['Organized By', ["Date",'date'],['Time','time'], 'Location',['Description','textarea']];

async function showPopUp(e) {
    let eventCard = e.target;
    while(eventCard.className !== 'event-card') {
        eventCard = eventCard.parentNode;
    }

    let event = await getData('./events/popup', 'POST', {"event.eventID": eventCard.id});
    event = event['event'];

    popupEvent.clearPopUp();
    popupEvent.setHeader('theme',event,'name');
    popupEvent.startPopUpInfo();
    popupEvent.showStatus(event['status']);
    popupEvent.showParticipants(event['participationCount']);
    popupEvent.endPopUpInfo();

    popupEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    popupEvent.setButtons([{text:'Going',classes:['btn-primary'],value:event['eventID'],func:markParticipation }]);
    popupEvent.showPopUp();
}

let markParticipation = async (e) => {

    if(e.target.innerHTML === "Going") {
        let eventID = e.target.value;

        e.target.innerHTML = "Not Going";
        e.target.classList.remove('btn-primary');
        e.target.classList.add('btn-danger');
    }
    else {
        let eventID = e.target.value;

        e.target.innerHTML = "Going";
        e.target.classList.remove('btn-danger');
        e.target.classList.add('btn-primary');
    }
}


