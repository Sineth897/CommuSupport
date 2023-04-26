import {getData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";
import {PopUp} from "../../popup/popUp.js";

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

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');
const searchBtn = document.getElementById('searchBtn');

const vehicleTypeFilter = document.getElementById('vehicleTypeFilter');
const preferenceFilter = document.getElementById('preferenceFilter');

const ageSort = document.getElementById('ageSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {

    let filters = {};

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

    // console.log(result['drivers']);

    const tableData = {
        headings: ['Name','Contact Number','Vehicle', 'Vehicle Number', 'Preference',],
        keys: ['name','contactNumber','vehicleType', 'vehicleNo', 'preference',['','View','#',[],'driverID']],
        data: result['drivers'],
    }

    displayTable(driverTableDiv,tableData);
    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    let viewBtns = document.querySelectorAll('a.btn-primary');

    for(let i=0;i<viewBtns.length;i++) {
        viewBtns[i].addEventListener('click', showDriverPopup);
    }

});

sortBtn.addEventListener('click', async function() {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
})

let viewBtns = document.querySelectorAll('a.btn-primary');

for(let i=0;i<viewBtns.length;i++) {
    viewBtns[i].addEventListener('click', showDriverPopup);
}

const popup = new PopUp();

async function showDriverPopup(e) {
    const employeeID = e.target.id;

    const result = await getData('./driver/popup', 'POST', {employeeID:employeeID});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error', value:result['msg']});
        return
    }

    const data = result["data"];


    popup.clearPopUp();
    popup.setHeader('Driver Details');

    // popup.startSplitDiv();
    popup.startDiv()
    popup.setSubHeading('Personal Details');
    popup.startSplitDiv();
    popup.setBody(data['driver'],['name','gender','preference'],['Name','Gender','Preference']);
    popup.setBody(data['driver'],['age','contactNumber'],['Age','Contact Number']);
    popup.endSplitDiv();
    popup.endDiv();

    popup.startDiv();

    popup.startSplitDiv();
    popup.startDiv();
    popup.setSubHeading('Vehicle Details');
    popup.setBody(data['driver'],['vehicleType','vehicleNo'],['Vehicle Type','Vehicle Number']);
    popup.endDiv();

    popup.startDiv();
    popup.setSubHeading('Assigned Delivery Details');
    popup.setBody(data['deliveryInfo'],['Ongoing','Completed'],['Ongoing','Completed']);
    popup.endDiv();
    popup.endSplitDiv();
    popup.endDiv();

    // popup.endSplitDiv();

    popup.showPopUp();

}



