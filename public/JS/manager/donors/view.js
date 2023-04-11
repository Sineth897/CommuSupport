import {getData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";
import {displayTable} from "../../components/table.js";

const toggle = new togglePages([{btnId:'individual',pageId:'individualDonorDisplay'},{btnId:'organization',pageId:'organizationDonorDisplay'}]);

const individualDonorDisplay = document.getElementById('individualDonorDisplay');
const organizationDonorDisplay = document.getElementById('organizationDonorDisplay');

const sortOptions = document.getElementById('sortOptions');

document.getElementById('sort').addEventListener('click', function(e) {
    if(sortOptions.style.display === 'block') {
        sortOptions.style.display = 'none';
    } else {
        sortOptions.style.display = 'block';
    }
});

const sortBtn = document.getElementById('sortBtn');
const searchBtn = document.getElementById('searchBtn');

const registeredDateSort = document.getElementById('registeredDateSort');

const searchInput = document.getElementById('searchInput');

sortBtn.addEventListener('click', async function(e) {

    let sort = {DESC: []};

    if(registeredDateSort.checked) {
        sort['DESC'].push('registeredDate');
    }

    let search = searchInput.value;

    let data = await getData('./donors/filter','post',{sort:sort,search:search});

    // console.log(data);

    if(!data['status']) {
        flash.showMessage({type:'error',value:data['msg']},3000);
        return;
    }

    const individualDonorTableData = {
        headings: ['First Name','Last name','Contact Number','Email'],
        keys: ['fname','lname','contactNumber','email',['','View','#',[],'donorID']],
        data: data['individualDonors']
    }

    const organizationDonorTableData = {
        headings: ['Organization Name','Representative Name','Contact Number','Email',],
        keys: ['organizationName','representative','contactNumber','email',['','View','#',[],'donorID']],
        data: data['organizationDonors']
    }

    displayTable(individualDonorDisplay,individualDonorTableData);
    displayTable(organizationDonorDisplay,organizationDonorTableData);

    sortOptions.style.display = 'none';

});

searchBtn.addEventListener('click', async function(e) {
    sortBtn.click();
});