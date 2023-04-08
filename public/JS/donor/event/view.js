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

    let event = await getData('./event/popup', 'POST', {"event.eventID": eventCard.id});
    let participationFlag = event['isGoing'];
    event = event['event'];

    popupEvent.clearPopUp();
    popupEvent.setHeader('theme',event,'name');
    popupEvent.startPopUpInfo();
    popupEvent.showStatus(event['status']);
    popupEvent.showParticipants(event['participationCount']);
    popupEvent.endPopUpInfo();

    popupEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    if(participationFlag) {
        popupEvent.setButtons([{text:'Not Going',classes:['btn-danger'],value:event['eventID'],func:markParticipation }]);
    } else {
        popupEvent.setButtons([{text:'Going',classes:['btn-primary'],value:event['eventID'],func:markParticipation }]);
    }
    popupEvent.showPopUp();
}

let markParticipation = async (e) => {

    if(e.target.innerHTML === "Going") {
        let eventID = e.target.value;

        let result = await getData('./event/markParticipation', 'POST', {eventID: eventID});

        if(result['status']) {
            e.target.innerHTML = "Not Going";
            e.target.classList.remove('btn-primary');
            e.target.classList.add('btn-danger');
        } else {
            console.log(result);
            alert('Something went wrong');
        }
    }
    else {
        let eventID = e.target.value;

        let result = await getData('./event/markParticipation', 'POST', {eventID: eventID});

        if(result['status']) {
            e.target.innerHTML = "Going";
            e.target.classList.remove('btn-danger');
            e.target.classList.add('btn-primary');
        } else {
            console.log(result);
            alert('Something went wrong');
        }
    }

    popupEvent.hidePopUp();
    document.getElementById('filterBtn').click();
    document.getElementById(e.target.value).click();
}

let filterOptions = document.getElementById('filterOptions');
document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
    sortOptions.style.display = 'none';
});

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let eventCategory = document.getElementById('eventCategory');
let sortByDate = document.getElementById('sortByDate');

filterBtn.addEventListener('click', async function() {

    let filterValues = {};
    let sort = {};

    if (eventCategory.value) {
        filterValues['eventCategoryID'] = eventCategory.value;
    }

    if (sortByDate.checked) {
        sort['DESC'] = ['date'];
    }

    let array = await getData('./event/filter', 'POST', {filters:filterValues, sortBy:sort});

    filterOptions.style.display = 'none';
    displayEventcards(eventsDiv,array);
    updateEventCardOnClick();

});

function updateEventCardOnClick() {
    let eventCards = document.getElementsByClassName('event-card');
    for(let i = 0; i < eventCards.length; i++) {
        eventCards[i].addEventListener('click', (e) => showPopUp(e));
    }
}

let sortOptions = document.getElementById('sortOptions');

document.getElementById('sort').addEventListener('click', function(e) {
    if(sortOptions.style.display === 'block') {
        sortOptions.style.display = 'none';
    } else {
        sortOptions.style.display = 'block';
    }
    filterOptions.style.display = 'none';
});

document.getElementById('sortBtn').addEventListener('click', function() {
    filterBtn.click();
    sortOptions.style.display = 'none';
});
