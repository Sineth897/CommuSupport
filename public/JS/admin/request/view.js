import {getData} from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";
import togglePages from "../../togglePages.js";

const toggle = new togglePages([{btnId:'pending',pageId:'pendingRequestsTable'},{btnId:'accepted',pageId:'acceptedRequestsTable'}],'block');

const pendingRequestsTableDiv = document.getElementById('pendingRequestsTable');
const acceptedRequestsTableDiv = document.getElementById('acceptedRequestsTable');

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

const subcategoryFilter = document.getElementById('subcategoryFilter');
const approvalFilter = document.getElementById('approvalFilter');

const postedDateSort = document.getElementById('postedDateSort');
const amountSort = document.getElementById('amountSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function() {
    let filters = {};

    if(subcategoryFilter.value) {
        filters['item'] = subcategoryFilter.value;
    }

    if(approvalFilter.value) {
        filters['approval'] = approvalFilter.value;
    }

    let sort = {DESC:[]};

    if(postedDateSort.checked) {
        sort['DESC'].push('postedDate');
    }

    if(amountSort.checked) {
        sort['DESC'].push('amount');
    }

    let search = '';

    if(searchInput.value) {
        search = searchInput.value;
    }

    const data = await getData('./requests/filter','post',{filters:filters,sort:sort,search:search});

    if(!data['status']) {
        flash.showMessage({type:'error',value:data['message']});
        return;
    }

    // console.log(data);

    const pendingRequestsTableData = {
        headings: ["PostedBy", "Approval","Item",	"Amount","Posted Date"],
        keys: ["username","approval","subcategoryName","amount", "postedDate",['','View','#',[],'requestID']],
        data: data['pendingRequests']
    }

    const acceptedRequestsTableData = {
        headings: ["Posted By", "Accepted By","Item",	"Amount","Delivery"],
        keys: ["username","acceptedBy","subcategoryName","amount", "deliveryStatus",['','View','#',[],'acceptedID']],
        data: data['acceptedRequests']
    }

    displayTable(pendingRequestsTableDiv, pendingRequestsTableData);
    displayTable(acceptedRequestsTableDiv, acceptedRequestsTableData);

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

});

sortBtn.addEventListener('click', async function() {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function() {
    filterBtn.click();
});
