import togglePages from "../../togglePages.js";
import {PopUp} from "../../popup/popUp.js";
import donationCard from "../../components/donationCard.js";
import {getData} from "../../request.js";
import flash from "../../flashmessages/flash.js";

let toggle = new togglePages([
                                {btnId:'active',pageId:'activeDonations',title:'Active Donations'},
                                {btnId:'completed',pageId:'completedDonations',title:'Completed Donations'}],
                                'grid');

const popup = new PopUp();

let donationBtns = document.querySelectorAll('.don-del-primary');

for(let i=0;i<donationBtns.length;i++) {
    donationBtns[i].addEventListener('click', showDonationPopUp);
}

function getParentWithID(element) {
    let parent = element;
    while(!parent.id) {
        parent = parent.parentNode;
    }
    return parent;
}

async function showDonationPopUp(e) {

    const parent = getParentWithID(e.target);

    const donationID = parent.id;

    const result = await getData('./donation/popup','post',{donationID:donationID});

    console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:result['msg']},3000);
        return;
    }

    const donation = result['donation'];
    const deliveries = result['deliveries'];

    popup.clearPopUp();
    popup.setHeader('Donation Details');
    popup.setComplaintIcon(parent.id,"donation");

    popup.startSplitDiv();
    popup.setBody(donation,['city','subcategoryName'],['To',"Item"]);
    popup.setBody(donation,['date','amount'],['Created Date',"Amount"]);
    popup.endSplitDiv();


    popup.setDeliveryDetails(deliveries);

    popup.showPopUp();

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

const ongoingDonationsDiv = document.getElementById('activeDonations');
const completedDonationsDiv = document.getElementById('completedDonations');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const categoryFilter = document.getElementById('filterCategory');

const sortDate = document.getElementById('sortDate');
const sortAmount = document.getElementById('sortAmount');

const donationCards = new donationCard('donor');

filterBtn.addEventListener('click', async function(e) {

    let filters = {};

    if(categoryFilter.value) {
        filters['item'] = categoryFilter.value;
    }

    let sort = {DESC:[]};

    if(sortDate.checked) {
        sort.DESC.push('date');
    }

    if(sortAmount.checked) {
        sort.DESC.push('amount');
    }

    const result = await getData('./donations/filter','post',{filters:filters,sort:sort});

    // console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:result['msg']},3000);
        return;
    }

    toggle.removeNoData();

    const donations = result['donations'];

    const completedDonations = donations.filter(donation => donation['deliveryStatus'] === 'Completed');
    const ongoingDonations = donations.filter(donation => donation['deliveryStatus'] === 'Not Assigned' || donation['deliveryStatus'] === 'Ongoing');

    donationCards.displayDonationCards(ongoingDonationsDiv,ongoingDonations);
    donationCards.displayDonationCards(completedDonationsDiv,completedDonations);

    toggle.checkNoData();

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    let donationBtns = document.querySelectorAll('.don-del-primary');

    for(let i=0;i<donationBtns.length;i++) {
        donationBtns[i].addEventListener('click', showDonationPopUp);
    }

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});