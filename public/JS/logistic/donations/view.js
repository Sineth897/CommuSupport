import togglePages from "../../togglePages.js";
import donationCard from "../../components/donationCard.js";
import {getData} from "../../request.js";
import flash from "../../flashmessages/flash.js";

let toggle = new togglePages([{btnId:'ongoing',pageId:'ongoingDonations'},{btnId:'completed',pageId:'completedDonations'}],'grid');

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

const ongoingDonationsDiv = document.getElementById('ongoingDonations');
const completedDonationsDiv = document.getElementById('completedDonations');

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');

const categoryFilter = document.getElementById('filterCategory');

const sortDate = document.getElementById('sortDate');
const sortAmount = document.getElementById('sortAmount');

const donationCards = new donationCard('employee');

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

    const donations = result['donations'];

    const completedDonations = donations.filter(donation => donation['deliveryStatus'] === 'Completed');
    const ongoingDonations = donations.filter(donation => donation['deliveryStatus'] === 'Not Assigned' || donation['deliveryStatus'] === 'Ongoing');

    donationCards.displayDonationCards(ongoingDonationsDiv,ongoingDonations);
    donationCards.displayDonationCards(completedDonationsDiv,completedDonations);

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});