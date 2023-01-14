import request from "../../request.js";
import eventCards from "../../components/eventcard.js";

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let eventCategory = document.getElementById('eventCategory');
let samCC = document.getElementById('sameCC');

filterBtn.addEventListener('click', async function() {

    let filterValues = {};

    if (eventCategory.value) {
        filterValues['eventCategory'] = eventCategory.value;
    }
    if(samCC.checked) {
        filterValues['ccID'] = samCC.value;
    }

    let array = await request().getData('./events/filter', 'POST', filterValues);

    eventCards().displayEventcards(eventsDiv,array);

});

