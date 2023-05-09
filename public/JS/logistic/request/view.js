import togglePages from "../../togglePages.js";
import {getData,getTextData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import flash from "../../flashmessages/flash.js";
import requestCard from "../../components/requestcard.js";

let inventory = {};

async function updatedInventory() {
    const result = await getData('./inventory/getcurrentinventory', 'POST', {});

    if(!result['status']) {
        flash.showMessage({type:'error',value:'Error cannot get inventory details! Please try again later!'});
        return;
    }

    inventory = result['inventory'];

}

let toggle = new togglePages(
    [
            {btnId:'posted',pageId:'postedRequests',title:"Posted Requests"},
            {btnId:'accepted',pageId:'acceptedRequests',title:'Accepted Requests'},
            {btnId: 'completed',pageId:'completedRequests',title:'Completed Requests'}],
        'grid');

let popUpRequest = new PopUp();

let popUpFunctions = new PopUpFunctions();

let requests = document.querySelectorAll('.requestView');

requests = Array.from(requests);

for(let i = 0; i < requests.length; i++) {
    requests[i].addEventListener('click', (e) => showReqPopUp(e));
}

async function showReqPopUp(e) {
    let element = e.target;
    while(element.id === '') {
        element = element.parentElement;
    }

    let result = await getData('./requests/popup', 'POST', {"r.requestID": element.id});

    // console.log(result);

    let data = result['requestDetails'];

    if( element.id.includes('accepted')) {
        popUpRequest.clearPopUp();
        popUpRequest.setHeader('Request Details');



        popUpRequest.startSplitDiv();
        popUpRequest.setBody(data,['acceptedDate','subcategoryName','notes'],['Accepted Date','Item',['Notes','textarea']]);

        popUpRequest.setBody(data,['urgency','amount',],['Urgency','Amount',]);
        popUpRequest.endSplitDiv();

        popUpRequest.setDeliveryDetails(result['deliveries']);


        popUpRequest.showPopUp();
        return;
    }

    popUpRequest.clearPopUp();
    popUpRequest.setHeader('Request Details');

    popUpRequest.startSplitDiv();
    popUpRequest.startSplitDiv();
    popUpRequest.setBody(data,['postedDate','subcategoryName','urgency'],['Date Posted','Item','Urgency']);

    popUpRequest.endSplitDiv();
    popUpRequest.setBody(data,['expDate','amount'],['Valid until','Amount']);
    popUpRequest.endSplitDiv();

    popUpRequest.setButtons([{text:'Accept',classes:['btn-primary'],value:data['requestID'],func:acceptFun,cancel:false},]);
    popUpRequest.showPopUp();


}

const acceptFun = async (e) => {
    const reqId = e.target.value;
    const popUp = document.querySelector('#popUpContainer');
    popUp.style.display = 'none';

    showAcceptPopUp(popUp,e.target.value);


}

const showAcceptPopUp = async (popUp,reqId) => {
    await updatedInventory();
    const amount = popUp.querySelector('#amount').value;
    const item = popUp.querySelector('#subcategoryName').value;
    const available = inventory[item] === undefined ? 0 : inventory[item];

    const amountField = `<div class="form-group"><label class="form-label">Amount</label><input class="basic-input-field" id="amount" type="text" value="${amount}" disabled=""></div>`;
    const itemField = `<div class="form-group"><label class="form-label">Item</label><input class="basic-input-field" id="item" type="text" value="${item}" disabled=""></div>`;
    const max = parseInt(amount) > parseInt(available) ? parseInt(available) : parseInt(amount);

    let acceptedValue = `<input type="number" id="acceptedAmount" value="${parseInt(amount)}" min="1" max="${max}"/>`;
    let availableValue = `<p style="font-size: .8rem"> There are ${available} available in stock</p>`;

    let buttons = `<div class='popup-btns'>
    <button class='btn btn-primary' id='confirm' value="${reqId}">Confirm</button>
    <button class='btn btn-secondary' id='cancel'>Cancel</button>
    </div>`

    if(max === 0) {
        acceptedValue =``;
        availableValue = `<p style="font-size: .8rem"><strong> There are no items available in stock </strong></p>`;
        buttons = `<div class='popup-btns' style="justify-content: end">
                        <button class='btn btn-secondary' id='cancel'>Cancel</button>
                   </div>`;
    }

    const acceptPopUp = document.createElement('div');
    acceptPopUp.id = 'acceptPopUp';
    acceptPopUp.classList.add('popup');
    acceptPopUp.innerHTML = `<div class='form-split'> ${itemField} ${amountField}</div>` + acceptedValue + availableValue + buttons;

    acceptPopUp.querySelector('#cancel').addEventListener('click', () => cancelAccept());
    if(acceptPopUp.querySelector('#confirm')) {
        acceptPopUp.querySelector('#confirm').addEventListener('click', (e) => confirm(e));
    }

    document.querySelector('#popUpBackground').appendChild(acceptPopUp);
}

const cancelAccept = () => {
    const acceptPopUp = document.querySelector('#acceptPopUp');
    acceptPopUp.remove();
    document.querySelector('#popUpContainer').style.display = 'block';
}

const confirm = async (e) => {
    const acceptPopUp = document.querySelector('#acceptPopUp');
    const acceptedAmount = acceptPopUp.querySelector('#acceptedAmount').value;
    const amount = acceptPopUp.querySelector('#amount').value;
    const reqId = acceptPopUp.querySelector('#confirm').value;

    let result = await getData('./requests/accept', 'POST', {"requestID": reqId, "amount": acceptedAmount, "remaining": amount-acceptedAmount});

    if(result['success']) {
        flash.showMessage({type:'success',value:'Request accepted successfully!'});
        popUpRequest.clearPopUp();
        popUpRequest.hidePopUp();
        acceptPopUp.remove();
        if(result['remove']) {
            document.getElementById(reqId).querySelector('button').remove();
        }
        document.querySelector('#popUpContainer').style.display = 'block';
        document.getElementById(reqId).querySelector('button').click();
    } else {
        flash.showMessage({type:'error',vallue:'Something went wrong! Please try again later!'});
        console.log(result);
    }


}

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

const requestDisplay = document.getElementById('postedRequests');
const acceptedDisplay = document.getElementById('acceptedRequests');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const item = document.getElementById('filterCategory');
const urgency = document.getElementById('filterUrgency');

const sortDatePosted = document.getElementById('sortByDatePosted');
const amount = document.getElementById('sortByAmount');

filterBtn.addEventListener('click', async function(e) {
    let filter = {
        approval:'Approved'
    };

    if(item.value) {
        filter['item'] = item.value;
    }

    if(urgency.value) {
        filter['urgency'] = urgency.value;
    }

    let sort = {DESC:[]};

    if(sortDatePosted.checked) {
        sort['DESC'].push('postedDate');
    }

    if(amount.checked) {
        sort['DESC'].push('amount');
    }

    const result = await getData('./requests/filter', 'POST', {filters:filter, sort:sort});

    console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:'Something went wrong! Please try again later!'});
        return;
    }

    const requests = result['requests'];
    const acceptedRequests = result['acceptedRequests'];

    requestDisplay.innerHTML = '';
    requestCard.showCards(requests,requestDisplay,[["View","requestView"]]);

    acceptedDisplay.innerHTML = '';
    requestCard.showCards(acceptedRequests,acceptedDisplay,[["View","requestView"]],true);

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    const requestCards = document.querySelectorAll('.requestView');

    for(let i = 0; i < requestCards.length; i++) {
        requestCards[i].addEventListener('click',showReqPopUp);
    }

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});