import {getData, getTextData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";
import requestcard from "../../components/requestcard.js";

let toggle = new togglePages([
                                        {btnId:'pending',pageId:'pendingRequests',title:'Pending Requests'},
                                        {btnId:'posted',pageId:'postedRequests',title:'Posted Requests'},
                                        {btnId:'history',pageId:'completedRequests',title:'Completed Requests'}],
                                        'grid');

function addEventListeners() {

    let requests = document.querySelectorAll('.pendingRequestView');
    requests = Array.from(requests);

    for(let i = 0; i < requests.length; i++) {
        requests[i].addEventListener('click', (e) => showPendingReqPopUp(e));
    }

    let postedRequests = document.querySelectorAll('.postedRequestView');
    postedRequests = Array.from(postedRequests);

    for(let i = 0; i < postedRequests.length; i++) {
        postedRequests[i].addEventListener('click', (e) => showPostedReqPopUp(e));
    }

    let completedRequests = document.querySelectorAll('.completedRequestView');
    completedRequests = Array.from(completedRequests);

    for(let i = 0; i < completedRequests.length; i++) {
        completedRequests[i].addEventListener('click', (e) => showCompletedReqPopUp(e));
    }

}

addEventListeners();

let popUpRequest = new PopUp();

async function showCompletedReqPopUp(e) {

    let parent = e.target.parentNode;

    while(!parent.id) {
        parent = parent.parentNode;
    }

    const result = await getData('./requests/popup/completed', 'POST', {"r.requestID": parent.id,'completed':true});

    console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:result['message']});
    }

    const request = result['request'];

    popUpRequest.clearPopUp();

    popUpRequest.setHeader('Request Details');
    popUpRequest.startSplitDiv();
    popUpRequest.setBody(request,['requestID','postedDate','subcategoryName'],['ID','Date Posted','Item']);
    popUpRequest.setBody(request,['address','urgency','amount'],['Address','Urgency','Amount']);
    popUpRequest.endSplitDiv()

    const acceptedUsers = document.createElement('div');
    acceptedUsers.innerHTML = `<p style="font-size: 0.9rem"> <strong>${request['users']} users</strong> have donated ${request['acceptedAmount']}</p>`;
    popUpRequest.container().append(acceptedUsers);

    popUpRequest.showPopUp();

}

async function showPostedReqPopUp(e) {

    let parent = e.target.parentNode;

    while(!parent.id) {
        parent = parent.parentNode;
    }

    const result = await getData('./requests/popup/posted', 'POST', {"r.requestID": parent.id,'completed':false});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:result['message']});
    }

    const request = result['request'];

    popUpRequest.clearPopUp();

    popUpRequest.setHeader('Request Details');
    popUpRequest.startSplitDiv();
    popUpRequest.setBody(request,['requestID','postedDate','subcategoryName'],['ID','Date Posted','Item']);
    popUpRequest.setBody(request,['address','urgency','amount'],['Address','Urgency','Amount']);
    popUpRequest.endSplitDiv()

    const acceptedUsers = document.createElement('div');
    acceptedUsers.innerHTML = `<p style="font-size: 0.9rem"> <strong>${request['users']} users</strong> have donated ${request['acceptedAmount']}</p>`;
    popUpRequest.container().append(acceptedUsers);

    popUpRequest.showPopUp();

}

async function showPendingReqPopUp(e) {

    let request = await getData('./requests/popup', 'POST', {"r.requestID": e.target.value});
console.log(request);
    let reqDetails = request['requestDetails'];
    let donee = request['donee'];
    console.log(donee);

    popUpRequest.clearPopUp();

    popUpRequest.startSplitDiv();
    popUpRequest.startSplitDiv();
    popUpRequest.insertHeading('Posted by');
    popUpRequest.endSplitDiv();
    popUpRequest.startSplitDiv();
    popUpRequest.insertHeading('Request Details');
    popUpRequest.endSplitDiv();
    popUpRequest.endSplitDiv();
    popUpRequest.startSplitDiv();

    popUpRequest.startSplitDiv();
    if(donee['type'] === 'Individual') {
        popUpRequest.setBody(donee,['name','contactNumber'],['Name','Contact Number']);
        popUpRequest.setBody(donee,['address','registeredDate'],['Address','Registered On']);
    } else {
        popUpRequest.setBody(donee,['organizationName','contactNumber','representative'],['Organization Name','Contact Number','Representative Name']);
        popUpRequest.setBody(donee,['address','registeredDate','representativeContact'],['Address','Registered On','Representative Contact']);
        if(donee['capacity']) {
            popUpRequest.setBody(donee,['capacity'],['Dependants']);
        }
    }
    popUpRequest.endSplitDiv();

    popUpRequest.startSplitDiv();
    popUpRequest.setBody(reqDetails,['requestID','postedDate','subcategoryName'],['ID','Date Posted','Item']);
    popUpRequest.setBody(reqDetails,['address','urgency','amount'],['Address','Urgency','Amount']);
    popUpRequest.endSplitDiv();
    popUpRequest.setBody(reqDetails,['notes'],[['Additional Notes','textarea']]);
    popUpRequest.setButtons([{text:'Approve',classes:['btn-primary'],value:reqDetails['requestID'],func:approveFun,cancel:true},
        {text:'Reject',classes:['btn-danger'],value:reqDetails['requestID'],func:rejectFun,cancel:true}]);
    popUpRequest.endSplitDiv();
    popUpRequest.showPopUp();
}

let popUpFunctions = new PopUpFunctions();


let approveFun = async (e) => {
    let btn = e.target;

    if(btn.innerHTML === 'Approve') {
        btn.innerHTML = 'Confirm';
        popUpFunctions.hideAllElementsWithin(btn.parentNode);
        btn.style.display = 'block';
        btn.nextElementSibling.style.display = 'block';
    }
    else {
        let requestData = {requestID:btn.value};
        let result = await getData('./request/approve', 'POST', {do:'approve',data:requestData});
        if(result['status']) {
            console.log('updated');
        } else {
            console.log(result);
        }
        popUpRequest.hidePopUp();
    }
}

let observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if(mutation.type === 'attributes' && mutation.attributeName === 'style' && mutation.target.style.display === 'none') {
            document.getElementById('rejectedReason').nextElementSibling.remove();
            document.getElementById('rejectedReason').remove();
        }
    });
});

let rejectFun = async (e) => {

    if(e.target.innerText === 'Reject') {
        e.target.innerText = 'Confirm';
        popUpFunctions.hideAllElementsWithin(e.target.parentNode);
        e.target.style.display = 'block';
        e.target.nextElementSibling.style.display = 'block';
        let parent = Object.assign(document.createElement('div'),{className:'popup-details',style:'width: 100%;margin: 0 auto 5px',rows:'5',id:'rejectedReason'});
        let reasonDiv = Object.assign(document.createElement('div'),{className:'form-group'});
        let reasonLabel = Object.assign(document.createElement('label'),{innerText:'Reason for rejection',className:'form-label'});
        let reasonInput = Object.assign(document.createElement('textarea'),{className:'basic-text-area',name:'rejectedReason'});
        reasonDiv.append(reasonLabel,reasonInput);
        parent.append(reasonDiv);
        e.target.parentNode.parentNode.insertBefore(parent,e.target.parentNode);
        e.target.parentNode.parentNode.insertBefore(document.createElement('div'),e.target.parentNode);

        observer.observe(e.target.nextElementSibling,{attributes:true,attributeFilter:['style']});

    }
    else {
        let requestData = {requestID: e.target.value,rejectedReason: document.getElementById('rejectedReason').querySelector('textarea').value};
        let result = await getData('./request/approve', 'POST', {do:'reject',data:requestData});
        if(result['status']) {
            console.log('updated');
        }
        else {
            console.log(result);
        }
        popUpRequest.hidePopUp();
    }

}

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

const pendingRequestsDiv = document.getElementById('pendingRequests');
const postedRequestsDiv = document.getElementById('postedRequests');
const completedRequestsDiv = document.getElementById('completedRequests');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const category = document.getElementById('filterCategory');

const datePosted = document.getElementById('sortByDatePosted');
const amount = document.getElementById('sortByAmount');

filterBtn.addEventListener('click', async function(e) {

    let filters = {};

    if(category.value) {
        filters['item'] = category.value;
    }

    let sort = {DESC:[]};

    if(datePosted.checked) {
        sort['DESC'].push('postedDate');
    }

    if(amount.checked) {
        sort['DESC'].push('amount');
    }

    const result = await getData('./requests/filter', 'POST', {filters:filters,sort:sort});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:'Something went wrong! Please try again later.'});
        return;
    }

    toggle.removeNoData();

    const requests = result['requests'];
    const completedRequests = result['completedRequests'];

    const pendingRequests = requests.filter((request) => {
        return request['approval'] === 'Pending';
    })

    const postedRequests = requests.filter((request) => {
        return request['approval'] === 'Approved';
    });

    // console.log(pendingRequests,postedRequests,completedRequests);

    pendingRequestsDiv.innerHTML = '';
    postedRequestsDiv.innerHTML = '';
    completedRequestsDiv.innerHTML = '';

    requestcard.showCards(pendingRequests,pendingRequestsDiv,[['View','pendingRequestView']]);
    requestcard.showCards(postedRequests,postedRequestsDiv,[['View','postedRequestView']]);
    requestcard.showCards(completedRequests,completedRequestsDiv,[['View','completedRequestView']],true);

    toggle.checkNoData();

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    addEventListeners();

});

sortBtn.addEventListener('click', async function(e) {
   filterBtn.click();
});


