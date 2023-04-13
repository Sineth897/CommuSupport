import {getData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";

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
        filters['category'] = categoryFilter.value;
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

    if(!data['status']) {
        flash.showMessage({type:'error', value:data['message']});
        return;
    }

    const tableData = {
        headings: ["Theme", "OrganizedBy", "Location", "Date", "Status",],
        keys: ["theme","organizedBy", "location", "date", "status",['','View','#',[],'eventID']],
        data: data['events']
    }

    displayTable(eventTableDiv, tableData);

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    // console.log(data);

});

sortBtn.addEventListener('click', async function() {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
});