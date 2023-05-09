import {getData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";

const donorTableDiv = document.getElementById('donorTable');

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
const typeFilter = document.getElementById('typeFilter');

const registeredDateSort = document.getElementById('registeredDateSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {
    let filters = {};

    if(ccFilter.value) {
        filters['ccID'] = ccFilter.value;
    }

    if(typeFilter.value) {
        filters['type'] = typeFilter.value;
    }

    let sort = {DESC:[]};

    if(registeredDateSort.checked) {
        sort['DESC'].push('registeredDate');
    }

    let search = '';

    if(searchInput.value) {
        search = searchInput.value;
    }

    let result = await getData('./donors/filter', 'POST', {filters:filters, sortBy:sort, search:search});

    if(!result['status']) {
        flash.showMessage({type:'error', value:result['msg']});
        return
    }

    result['donors'].forEach( (donor) => {
        donor['cc'] = result['CCs'][donor['ccID']];
    });

    const tableData = {
        headings: ['Username', 'Registered Date', 'Community Center', 'Contact Number', 'Type',],
        keys: ['username', 'registeredDate', 'cc', 'contactNumber', 'type', ['','View', '#', [], 'doneeID']],
        data: result['donors']
    }

    displayTable(donorTableDiv, tableData);

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';
    // console.log(result);

});

sortBtn.addEventListener('click', async function() {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
});