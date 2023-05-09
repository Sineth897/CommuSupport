import togglePages from "../../togglePages.js";
import {getData, getTextData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import flash from "../../flashmessages/flash.js";
import doneeRequestCard from "../../components/doneeRequestCard.js";


// page sof the requests view
let toggle = new togglePages(
        [
                {btnId:'active',pageId:'activeRequests',title:"Active Requests"},
                {btnId:'accepted',pageId:'acceptedRequests',title:'Accepted Requests' },
                {btnId:'completed',pageId:'completedRequests',title:'Completed Requests'}
            ],'grid');



// function to assign popup function to cards
function assignPopupFunctionToCards() {

    // get all accepted requests
    let cards = document.querySelectorAll('.viewRequest');

    // add event listener to each card
    for(let i=0;i<cards.length;i++) {
        cards[i].addEventListener('click', showPopup);
    }

    // get all completed requests
    let completedCards = document.querySelectorAll('.viewActiveRequest');

    // add event listener to each card
    for(let i=0;i<completedCards.length;i++) {
        completedCards[i].addEventListener('click', showPopup);
    }

}

// calling the function initially
assignPopupFunctionToCards();

function getParent(e) {
    let parent = e;
    while(!parent.id) {
        parent = parent.parentNode;
    }
    return parent;
}

// initialize popup class
const popUp = new PopUp();

// popup function
async function showPopup(e) {

    // get request ID and if the request is accepted, that ID
    const btn = e.target;
    const requestID = btn.value

    // get the parent element of the button and then the ID of the parent element
    const parent = getParent(btn.parentElement);
    const acceptedID = parent.id;

    // get the delivery status
    const deliveryStatus = parent.querySelector('.rq-delivery-status > p').innerHTML;

    // get the data from the server
    const result = await getData('./request/popup', 'POST',  {requestID: requestID,acceptedID:acceptedID,deliveryStatus:deliveryStatus} );

    console.log(result);

    // if the response is a error message
    if(!result['status']) {
        flash.showMessage({type:'error',value:result['message']});
        return;
    }

    // get the request details
    const request = result['requestDetails'];
    const deliveries = result['deliveries'];

    // set the popup
    popUp.clearPopUp();

    // add complaint for request
    popUp.setComplaintIcon(request['requestID'],'acceptedRequest');

    // pop up heading
    popUp.setHeader('Request Details');

    // if the request is completed add the number of users who have donated
    if(deliveryStatus === 'Completed') {
        const acceptedUsers = document.createElement('div');
        acceptedUsers.innerHTML = `<p style="font-size: 0.9rem"> <strong>${request['users']} users</strong> have donated </p>`;
        popUp.container().append(acceptedUsers);
    }

    // split the div
    popUp.startSplitDiv();
    popUp.setBody(request,['subcategoryName','postedDate','deliveryStatus'],['Item','Posted Date','Delivery']);
    popUp.setBody(request,['amount','approvedDate'],['Amount','Approved Date']);
    popUp.endSplitDiv();

    // separate field for notes included if provided
    if(request['notes']) {
        popUp.setBody(request,['notes'],[['Description','textarea']]);
    }

    // if the request is not completed add the delivery details
    if(deliveryStatus !== 'Completed') {
        popUp.setDeliveryDetails(deliveries);
    }

    //show popup
    popUp.showPopUp();

}

// get filter Options and sort Options
const filterOptions = document.getElementById('filterOptions');
const sortOptions = document.getElementById('sortOptions');

// add event listener to filter and sort buttons
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

// get the divs to show the deliveries
const activeRequestsDiv = document.getElementById('activeRequests');
const acceptedRequestsDiv = document.getElementById('acceptedRequests');
const completedRequestsDiv = document.getElementById('completedRequests');

// get filter and sort btns
const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

// filter options => by item
const item = document.getElementById('filterCategory');

// sort options => by created date, amount
const createdDate = document.getElementById('sortByDatePosted');
const amount = document.getElementById('sortByAmount');

// add event listener to filter and sort buttons
filterBtn.addEventListener('click', async function(e) {

    // get the filter values
    let filters = {};

    if(item.value) {
        filters['item'] = item.value;
    }

    // get the sort values
    let sort = {DESC:[]};

    if(createdDate.checked) {
        sort['DESC'].push('createdDate');
    }

    if(amount.checked) {
        sort['DESC'].push('amount');
    }

    // get the deliveries from the backend
    // const result = await getTextData('./requests/filter', 'POST', { filters:filters, sort:sort });
    const result = await getData('./requests/filter', 'POST', { filters:filters, sort:sort });

    console.log(result);

    // if error occurs, show error message
    if(!result['status']) {
        flash.showMessage({value: result['message'], type: 'error'});
        return;
    }

    const activeRequests = result['activeRequests'];
    const acceptedRequests = result['acceptedRequests'];
    const completedRequests = result['completedRequests'];

    toggle.removeNoData();

    doneeRequestCard.showPostedRequestCards(activeRequestsDiv,activeRequests);
    doneeRequestCard.showAcceptedRequestCards(acceptedRequestsDiv,acceptedRequests);
    doneeRequestCard.showCompletedRequestCards(completedRequestsDiv,completedRequests);

    toggle.checkNoData();

    // assign popup function to cards
    assignPopupFunctionToCards();

    // hide the filter and sort options
    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

});

// add event listener to sort button
sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});