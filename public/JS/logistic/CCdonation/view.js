import togglePages from "../../togglePages.js";
import { getData,getTextData } from "../../request.js";
import flash from "../../flashmessages/flash.js";
import {PopUp} from "../../popup/popUp.js";
import CCDonationCard from "../../components/ccDonationCard.js";

// toggle pages
let toggle = new togglePages([{btnId:'posted',pageId:'postedDonations'},{btnId:'ongoing',pageId:'ongoingDonations'},{btnId:'completed',pageId:'completedDonations'}],'grid');

// get all CC donation cards with a accept btn
let acceptBtns = document.querySelectorAll('.accept');

// add event listener click to execute acceptPopup function
for(let i = 0; i < acceptBtns.length; i++){
    acceptBtns[i].addEventListener('click',acceptPopup)
}

// to get the parent element with id once a the child is clicked
function getParent(element) {
    let parent = element;
    while(!parent.id) {
        parent = parent.parentElement;
    }
    return parent;
}

// function to get the accept popup to accept the CC donation
async function acceptPopup(e) {

    //get the parent and get the id of the CC donation
    const parent = getParent(e.target);

    const ccDonationID = parent.id;

    // fetch the data from the backend
    // do key refers to what to do in the backend
    const result = await getData('./CCdonation/accept', 'post',{ccDonationID:ccDonationID,do:'retrieve'});

    // console.log(result);

    // if the status is false then show the error message
    if(!result['status']) {
        flash.showMessage({'type':'error','value':result['message']});
        return;
    }

    // if the status is true then get data from the response
    const ccDonation = result['ccDonation'];
    const communitycenters = result['communitycenters'];
    const available = result['available'];

    // get the popup container
    const popupConatainer = document.querySelector('#popUpContainer');

    // add the structure of the popup to the popup container
    popupConatainer.innerHTML = structureAcceptPopup(ccDonation,communitycenters,available);

    // add event listener to the cancel btn and confirm btn
    popupConatainer.querySelector('#cancel').addEventListener('click',cancelFun);
    if(popupConatainer.querySelector('#confirm')) {
        popupConatainer.querySelector('#confirm').addEventListener('click',confirmFun);
    }

    // display the popup
    document.getElementById('popUpBackground').style.display = 'flex';

}

function structureAcceptPopup(ccDonation,communitycenters,available) {

    // heading of the popup
    const heading = `<h2 class="popup-header"> Donation Details</h2>`;

    // structure of the popup
    const requestedBy = `<div class="form-group"><label class="form-label">Requested By : </label><input class="basic-input-field" id="cc" type="text" value="${communitycenters[ccDonation['toCC']]}" disabled=""></div>`
    const item = `<div class="form-group"><label class="form-label">Requested Item : </label><input class="basic-input-field" id="item" type="text" value="${ccDonation['subcategoryName']}" disabled=""></div>`
    const amount = `<div class="form-group"><label class="form-label">Requested Amount : </label><input class="basic-input-field" id="amount" type="text" value="${ccDonation['amount']}" disabled=""></div>`
    const note = `<div class="form-group" style="padding-bottom: .5rem"><label class="form-label" style="margin-top: 0">Notes : </label><textarea class="basic-text-area description" disabled>${ccDonation['notes']}</textarea></div>`;

    // buttons of the popup
    let buttons = `<div class='popup-btns'>
    <button class='btn btn-primary' id='confirm' value="${ccDonation['ccDonationID']}">Confirm</button>
    <button class='btn btn-secondary' id='cancel'>Cancel</button>
    </div>`

    // first initialize the accepting message
    let acceptingMessage = ``;

    // check whether the requested item is in the community center inventory
    if(available.length !== 0) {

        // if the requested amount is less than or equal to the available amount then show the accepting message
        if(parseInt(available[0]['amount']) >= parseInt(ccDonation['amount'])) {

            // if both conditions are true then allow the user to accept the donation
            acceptingMessage = `<div class="form-group" style="gap: 0.2rem;align-items: center">
                                <p style="font-size: .8rem"> There are ${available[0]['amount']} in the inventory </p>
                                <strong style="font-size: .9rem"> Please confirm to accept </strong>
                          </div>`;
        }
        else {

            // if the requested amount is greater than the available amount then show relevant message
            acceptingMessage = `<div class="form group" ><p style="font-size: .8rem"> There are only ${available[0]['amount']} in the inventory </p></div>`
            buttons = `<div class='popup-btns'   style="justify-content:end">
                        <button class='btn btn-secondary' id='cancel'>Cancel</button>
                   </div>`
        }

    }
    else {

        // if the requested item is not in the inventory then show relevant message
        acceptingMessage = `<div class="form group"><p style="font-size: .8rem"> There are no items in the inventory </p></div>`
        buttons = `<div class='popup-btns' style="justify-content:end">
                        <button class='btn btn-secondary' id='cancel'>Cancel</button>
                   </div>`
    }

    // return the structure of the popup
    return heading + `<div class='form-split'> ${requestedBy} ${item} ${amount} </div>` + note + acceptingMessage  + buttons;

}

// cancel function which hide the popup after removing inner elements
const cancelFun = () => {
    document.querySelector('#popUpContainer').innerHTML = '';
    document.querySelector('#popUpBackground').style.display = 'none';
}

// confirm function which send the data to the backend to accept the donation
const confirmFun = async (e) => {

    // get the ccDonationID from the confirm button
    const ccDonationID = e.target.value;

    // fetch the data from the backend
    // do key refers to what to do in the backend
    const result = await getData('./CCdonation/accept', 'post',{ccDonationID:ccDonationID,do:'accept'});

    // console.log(result);

    // if the status is false then show the error message
    if(!result['status']) {
        flash.showMessage({'type':'error','value':result['message']});
        return;
    }

    // if the status is true then filter the page to show updated info
    filterBtn.click();

    // hide the popup
    document.querySelector('#popUpContainer').querySelector('#cancel').click();

}

// query all the view buttons
let viewBtns = document.querySelectorAll('.view');

// add event listener to all the view buttons
for(let i = 0; i < viewBtns.length; i++){
    viewBtns[i].addEventListener('click',viewPopup)
}

// initialize the popup class
const popup = new PopUp();

// function to view the popup
async function viewPopup(e) {

    // get the parent element of the view button and get the ccDonationID
    const parent = getParent(e.target);

    const ccDonationID = parent.id;

    // fetch the data from the backend
    const result = await getData('./CCdonation/popup', 'post',{ccDonationID:ccDonationID});

    // console.log(result);

    // if the status is false then show the error message
    if(!result['status']) {
        flash.showMessage({'type':'error','value':result['message']});
        return;
    }

    // if the status is true get the data from the response
    const ccDonation = result['ccDonation'];
    const deliveries = result['deliveries'];
    const communitycenters = result['communitycenters'];

    // get the other community center info
    // if the user is in accepting community center then get the posted by info
    // if the user is in posting community center then get the posted to info
    let otherCCInfo = parent.querySelector('.CC-donation-details').querySelectorAll('p')[1].innerHTML.split(' ');
    otherCCInfo = otherCCInfo[2] + ' ' + otherCCInfo[3];
    const otherCCArrayKey = otherCCInfo === 'Posted By:' ? 'toCity' : 'fromCity';

    // set the city of the community center using its ID
    ccDonation['fromCity'] = communitycenters[ccDonation['fromCC']] + 'CC';
    ccDonation['toCity'] = communitycenters[ccDonation['toCC']] + 'CC';

    // clear the popup and set the header
    popup.clearPopUp();
    popup.setHeader('Donation Details');

    // split the div into two
    popup.startSplitDiv();
    popup.setBody(ccDonation,['subcategoryName','createdDate'],['Item','Posted Date']);
    popup.setBody(ccDonation,['amount',otherCCArrayKey],['Amount',otherCCInfo]);
    popup.endSplitDiv();

    // show the notes of the donation
    popup.setBody(ccDonation,['notes'],[['Notes','textarea']]);

    // show the delivery details of the donation
    popup.setDeliveryDetails(deliveries);

    // show the popup
    popup.showPopUp();

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

const postedDonationsDiv = document.getElementById('postedDonations');
const ongoingDonationsDiv = document.getElementById('ongoingDonations');
const completedDonationsDiv = document.getElementById('completedDonations');

// get filter and sort btns
const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

// filter options => by item
const item = document.getElementById('filterCategory');

// sort options => by created date, amount
const createdDate = document.getElementById('sortCreatedDate');
const amount = document.getElementById('sortAmount');

// add event listener to filter and sort btns
filterBtn.addEventListener('click', async function(e) {

    // get filter values
    let filters = {};

    if(item.value) {
        filters['c.item'] = item.value;
    }

    // get sort values
    let sort = {DESC:[]};

    if(createdDate.checked) {
        sort['DESC'].push('c.createdDate');
    }

    if(amount.checked) {
        sort['DESC'].push('c.amount');
    }

    // fetch the data from the backend
    const result = await getData('./CCdonations/filter', 'post', {filters:filters, sort:sort});

    console.log(result);

    // if the status is false then show the error message
    if(!result['status']) {
        flash.showMessage({'type':'error','value':result['message']});
        return;
    }

    // if the status is true then get the data from the response
    const ccDonations = result['ccDonations'];
    const communitycenters = result['communitycenters'];
    const CC = result['CC'];

    // add city names of the community centers to the ccDonations
    for(let i = 0; i < ccDonations.length; i++) {

        // condition to check if the CC donation is accepted
        if(ccDonations[i]['fromCC']) {
            ccDonations[i]['fromCity'] = communitycenters[ccDonations[i]['fromCC']] + ' CC';
        }

        ccDonations[i]['toCity'] = communitycenters[ccDonations[i]['toCC']] + ' CC';

    }

    // posted donations which are not accepted and posted by other community centers
    const postedDonations = ccDonations.filter(ccDonation => ! ccDonation['fromCC'] && ccDonation['toCC'] !== CC);

    // ongoing donations which are accepted by logged in logistic officer or posted by logged in community center
    const ongoingDonations = ccDonations.filter(ccDonation => (ccDonation['fromCC'] === CC || ccDonation['toCC'] === CC) && !ccDonation['completedDate']);

    // completed donations which are accepted by logged in logistic officer or posted by logged in community center
    const completedDonations = ccDonations.filter(ccDonation => (ccDonation['fromCC'] === CC || ccDonation['toCC'] === CC) && ccDonation['completedDate']);

    // display the cards
    CCDonationCard.displayCards(postedDonationsDiv, postedDonations, CC, 'posted');
    CCDonationCard.displayCards(ongoingDonationsDiv, ongoingDonations, CC, 'ongoing');
    CCDonationCard.displayCards(completedDonationsDiv, completedDonations, CC, 'completed');

    // hide the filter and sort options
    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    // get all the card buttons
    let cardBtns = document.querySelectorAll('.CC-donation-primary');

    // add event listener to all the cards
    for(let i = 0; i < cardBtns.length; i++) {

        // assign event listener according to the button innerHTML
        if(cardBtns[i].innerHTML === 'Accept')  {
            cardBtns[i].addEventListener('click', acceptPopup);
        }
        else {
            cardBtns[i].addEventListener('click', viewPopup);
        }

    }

});

// add event listener to sort btn
sortBtn.addEventListener('click', async function(e) {

    // run filter function along with new options
    filterBtn.click();

});