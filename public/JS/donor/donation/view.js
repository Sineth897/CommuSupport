import togglePages from "../../togglePages.js";
import {PopUp} from "../../popup/popUp.js";

let toggle = new togglePages([{btnId:'active',pageId:'activeDonations'},{btnId:'completed',pageId:'completedDonations'}]);

const popup = new PopUp();

let donationBtns = document.querySelectorAll('.donation-view-btn');

console.log(donationBtns);

for(let i=0;i<donationBtns.length;i++) {
    donationBtns[i].addEventListener('click', showDonationPopUp);
}

function showDonationPopUp(e) {
    popup.clearPopUp();
    popup.setHeader('Donation Details');
    popup.setComplaintIcon(e.target.id,"donation");
    popup.showPopUp();
}