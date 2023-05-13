import {getData, getTextData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";
import togglePages from "../../togglePages.js";
import {PopUp} from "../../popup/popUp.js";

const toggle = new togglePages([
                                    {btnId:'events',pageId:'eventTable',title:''},
                                ]);

const eventTableDiv = document.getElementById('eventTable');

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

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');
const searchBtn = document.getElementById('searchBtn');

const ccFilter = document.getElementById('ccFilter');
const categoryFilter = document.getElementById('categoryFilter');
const statusFilter = document.getElementById('statusFilter')

const dateSort = document.getElementById('dateSort');
const participationCountSort = document.getElementById('participationCountSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {
    let filters = {};

    if(ccFilter.value) {
        filters['ccID'] = ccFilter.value;
    }

    if(categoryFilter.value) {
        filters['eventCategoryID'] = categoryFilter.value;
    }

    if(statusFilter.value) {
        filters['status'] = statusFilter.value;
    }

    let sort = {DESC:[]};

    if(dateSort.checked) {
        sort['DESC'].push('date');
    }

    if(participationCountSort.checked) {
        sort['DESC'].push('participationCount');
    }

    let search = '';

    if(searchInput.value) {
        search = searchInput.value;
    }

    let data = await getData('./events/filter', 'post',{filters:filters, sortBy:sort, search:search});

    // console.log(data);

    if(!data['status']) {
        flash.showMessage({type:'error', value:data['message']});
        return;
    }

    toggle.removeNoData();

    const tableData = {
        headings: ["Theme", "OrganizedBy", "Location", "Date", "Status",],
        keys: ["theme","organizedBy", "location", "date", "status",['','View','#',[],'eventID']],
        data: data['events']
    }

    displayTable(eventTableDiv, tableData);

    toggle.checkNoData();

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    assignEventListeners();

    // console.log(data);

});

sortBtn.addEventListener('click', async function() {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
});

function assignEventListeners() {

    const viewBtns = Array.from(document.getElementsByClassName('view'));

    viewBtns.forEach(function(btn) {
        btn.addEventListener('click', eventPopup);
    });

}

assignEventListeners();

const popUpEvent = new PopUp();

async function eventPopup(e) {

    const eventID = e.target.id;

    const result = await getData('./event/popup', 'post', {eventID:eventID});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error', value:result['message']});
        return;
    }

    const event = result['event'];

    popUpEvent.clearPopUp();

    popUpEvent.setHeader('theme',event,'name');

    popUpEvent.startPopUpInfo();
    popUpEvent.showStatus(event['status']);
    popUpEvent.showParticipants(event['participationCount']);
    popUpEvent.endPopUpInfo();

    popUpEvent.startSplitDiv();
    popUpEvent.setBody(event,['organizedBy','date'],['Organized By','Date']);
    popUpEvent.setBody(event,['cc','time'],['Within','Time']);
    popUpEvent.endSplitDiv();

    popUpEvent.setBody(event,
        ['location','description'],
        [['Location','textarea'],['Description','textarea']]);

    popUpEvent.showPopUp();

}