import {getData, getTextData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";
import togglePages from "../../togglePages.js";
import {PopUp} from "../../popup/popUp.js";

const toggle = new togglePages([
        {btnId:'logistic',pageId:'logisticDiv',title:'Logistic Officers'},
        {btnId:'manager',pageId:'managerDiv',title:'Managers'},
        ]);

const filterOptions = document.getElementById('filterOptions');
const sortOptions = document.getElementById('sortOptions');

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

const logisticDiv = document.getElementById('logisticDiv');
const managerDiv = document.getElementById('managerDiv');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');
const searchBtn = document.getElementById('searchBtn');

const genderFilter = document.getElementById('genderFilter');
const districtFilter = document.getElementById('choFilter');

const ageSort = document.getElementById('ageSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {

        let filters = {};

        if(genderFilter.value) {
                filters['gender'] = genderFilter.value;
        }

        if(districtFilter.value) {
                filters['cho'] = districtFilter.value;
        }

        let sort = {DESC:[]};

        if(ageSort.checked) {
                sort['DESC'].push('age');
        }

        const search = searchInput.value;

        const result = await getData('./employees/filter', 'post',{filters, sort, search});

        // console.log(result);

        if(!result['status']) {
                flash.showMessage({type:'error',value:'Unable to retrieve data from the database. Please try again later.'} );
                return;
        }

        toggle.removeNoData();

        const logisticOfficers = result['logisticOfficers'];
        const managers = result['managers'];

        const logisticTableData = {
                headings:['Name','Age',"Fender",'Contact number','Community Center'],
                keys: ['name','age','gender','contactNumber','city',['',"View",'#',[],'employeeID']],
                data: logisticOfficers
        }

        const managerTableData = {
                headings:['Name','Age',"Fender",'Contact number','Community Center'],
                keys: ['name','age','gender','contactNumber','city',['',"View",'#',[],'employeeID']],
                data: managers
        }

        displayTable(logisticDiv,logisticTableData);
        displayTable(managerDiv,managerTableData);

        toggle.checkNoData();

        filterOptions.style.display = 'none';
        sortOptions.style.display = 'none';

        assignEventListeners();

});

sortBtn.addEventListener('click', async function() {
        filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
        filterBtn.click();
});

searchInput.addEventListener('keyup', async function(e) {
        if(e.key === 'Enter') {
                filterBtn.click();
        }
});

function assignEventListeners() {

        const viewBtns = Array.from(document.getElementsByClassName('view'));

        viewBtns.forEach(btn => {
                btn.addEventListener('click', showEmployeePopup);
        });

}

assignEventListeners();

const popup = new PopUp();

async function showEmployeePopup(e) {

        const employeeID = e.target.id;

        const result = await getData('./employees/popup', 'post', {employeeID});

        // console.log(result)

        if(!result['status']) {
                flash.showMessage({type:'error',value:'Unable to retrieve data from the database. Please try again later.'} );
                return;
        }

        const employee = result['employee'];

        console.log(employee);

        popup.clearPopUp();
        popup.setHeader('Employee Details');

        popup.startSplitDiv();

        popup.setBody(employee,['name','age','contactNumber'],['Name','Age','Contact Number']);
        popup.setBody(employee,['gender','city','fax'],['Gender','Community Center','Fax']);

        popup.endSplitDiv();

        popup.setBody(employee,['address'],[['Address','textarea']]);

        popup.showPopUp();


}