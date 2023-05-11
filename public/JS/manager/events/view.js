import {getData,getTextData} from "../../request.js";
import {displayEventcards} from "../../components/eventcard.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";

let toggle = new togglePages([
                                {btnId:'upcoming',pageId:'upcomingEvents',title:'Upcoming Events'},
                                {btnId:'completed',pageId:'finishedEvents',title:'Completed Events'},
                                {btnId:'cancelled',pageId:'cancelledEvents',title:'Cancelled Events'}]
                                ,'grid');

let filterOptions = document.getElementById('filterOptions');
let sortOptions = document.getElementById('sortOptions');

document.getElementById('filter').addEventListener('click', function(e) {

   if(filterOptions.style.display === 'block') {
       filterOptions.style.display = 'none';
   } else {
       filterOptions.style.display = 'block';
   }
    sortOptions.style.display = 'none';
    // console.log(filterOptions.style.display);
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

let filterBtn = document.getElementById('filterBtn');
const upcomingEventsDiv = document.getElementById('upcomingEvents');
const finishedEventsDiv = document.getElementById('finishedEvents');
const cancelledEventsDiv = document.getElementById('cancelledEvents');

let eventCategory = document.getElementById('eventCategory');
let sortByDate = document.getElementById('sortByDate');
let sortByParticipation = document.getElementById('sortByParticipation');
// console.log(sortByParticipation);

const popUpArrayKeys = ['organizedBy','date','time','location','description'];
const popUpArrayLabels = ['Organized By', ["Date",'date'],['Time','time'], 'Location',['Event Description','textarea']];

updateEventCardOnClick();
filterBtn.addEventListener('click', async function() {

    let filterValues = {};
    let sort = {DESC:[]};

    if (eventCategory.value) {
        filterValues['eventCategoryID'] = eventCategory.value;
    }

    if (sortByDate.checked) {
        sort['DESC'].push('date');
    }

    if (sortByParticipation.checked) {
        sort['DESC'].push('participationCount');
    }

    const array = await getData('./event/filter', 'POST', {filters:filterValues, sortBy:sort});

    toggle.removeNoData();

    // console.log(array);

    const upcomingEvents = {
        icons : array['icons'],
        event : array['event'].filter((event) => event['status'] === 'Upcoming'),
    };

    const finishedEvents = {
        icons : array['icons'],
        event : array['event'].filter((event) => event['status'] === 'Finished'),
    }

    const cancelledEvents = {
        icons : array['icons'],
        event : array['event'].filter((event) => event['status'] === 'Cancelled'),
    }

    filterOptions.style.display = 'none';


    displayEventcards(upcomingEventsDiv,upcomingEvents);
    displayEventcards(finishedEventsDiv,finishedEvents);
    displayEventcards(cancelledEventsDiv,cancelledEvents);

    toggle.checkNoData();

    updateEventCardOnClick();

});

document.getElementById('sortBtn').addEventListener('click', function() {
    filterBtn.click();
    sortOptions.style.display = 'none';
});

function updateEventCardOnClick() {
    let eventCards = document.getElementsByClassName('event-card');
    for(let i = 0; i < eventCards.length; i++) {
        eventCards[i].addEventListener('click', (e) => showPopUp(e));
    }
}

let popUpEvent = new PopUp();

async function showPopUp(e) {
    let eventCard = e.target;
    while(eventCard.className !== 'event-card') {
        eventCard = eventCard.parentNode;
    }
    let event = await getData('./event/popup', 'POST', {"event.eventID": eventCard.id});
    let eventIcons = event['icons'];
    event = event['event'];

    popUpEvent.clearPopUp();
    popUpEvent.setHeader('theme',event,'name');

    popUpEvent.startPopUpInfo();
    popUpEvent.showStatus(event['status']);
    popUpEvent.showParticipants(event['participationCount']);
    popUpEvent.endPopUpInfo();

    popUpEvent.setBody(event,popUpArrayKeys,popUpArrayLabels);
    if(event['status'] === 'Upcoming') {
        popUpEvent.setButtons([{text:'Update',classes:['btn-primary'],value:event['eventID'],func:updateFunc,cancel:true},
            {text:'Cancel Event',classes:['btn-danger'],value:event['eventID'],func:cancelFunc,cancel:true}]);
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
        let result = await getData('./event/update', 'POST', {do:'update',data:updateValues});
        if(result['status']) {
            flash.showMessage({type:'success',value:'Event Updated Successfully'},3000);
        } else {
            flash.showMessage({type:'error',value:'Event Update Failed'},3000);
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
        let result = await getData('./event/update', 'POST', {do:'cancel',data:e.target.value});
        if(result['status']) {
            flash.showMessage({type:'success',value:'Event Cancel Successfully'},3000);
        } else {
            flash.showMessage({type:'error',value:'Event cancel Failed'},3000);
        }
        popUpEvent.hidePopUp();
        filterBtn.click();
        e.target.innerText = 'Cancel Event';
    }
}