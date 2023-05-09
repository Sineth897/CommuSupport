import {getData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";

const driverTableDiv = document.getElementById('driverTable');

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
const vehicleTypeFilter = document.getElementById('vehicleTypeFilter');
const preferenceFilter = document.getElementById('preferenceFilter');

const ageSort = document.getElementById('ageSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {

    let filters = {};

    if(ccFilter.value) {
        filters['ccID'] = ccFilter.value;
    }

    if(vehicleTypeFilter.value) {
        filters['vehicleType'] = vehicleTypeFilter.value;
    }

    if(preferenceFilter.value) {
        filters['preference'] = preferenceFilter.value;
    }

    let sort = {DESC:[]};

    if(ageSort.checked) {
        sort['DESC'].push('driver.age');
    }

    let search = '';

    if(searchInput.value !== '') {
        search = searchInput.value;
    }

    let result = await getData('./drivers/filter', 'POST', {filters:filters, sortBy:sort, search:search});

    if(!result['status']) {
        flash.showMessage({type:'error', value:result['msg']});
        return
    }

    result['drivers'].forEach( (driver) => {
        driver['cc'] = result['CCs'][driver['ccID']];
    });

    // console.log(result['drivers']);

    const tableData = {
        headings: ['Name', 'Age', 'Contact Number', 'Vehicle', 'Vehicle No', 'Community Center'],
        keys: ['name', 'age', 'contactNumber', 'vehicleType', 'vehicleNo', 'cc',['','View', '#', [], 'driverID']],
        data: result['drivers'],
    }

    displayTable(driverTableDiv,tableData);
    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

});

sortBtn.addEventListener('click', async function() {
   filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
});