import togglePages from "../../togglePages.js";
import { getData,getTextData } from "../../request.js";

let toggle = new togglePages([{btnId:'posted',pageId:'postedDonations'},{btnId:'ongoing',pageId:'ongoingDonations'},{btnId:'completed',pageId:'completedDonations'}],'grid');

let acceptBtns = document.querySelectorAll('.accept');

for(let i = 0; i < acceptBtns.length; i++){
    acceptBtns[i].addEventListener('click',acceptPopup)
}

function getParent(element) {
    let parent = element;
    while(!parent.id) {
        parent = parent.parentElement;
    }
    return parent;
}

async function acceptPopup(e) {

    const parent = getParent(e.target);

    const ccDonationID = parent.id;

    const result = await getData('./CCdonation/accept', 'post',{ccDonationID:ccDonationID,do:'retrieve'});

    // console.log(result);

    if(!result['status']) {
        alert(result['message']);
        return;
    }

    const ccDonation = result['ccDonation'];
    const communitycenters = result['communitycenters'];

    const popupConatainer = document.querySelector('#popUpContainer');

    popupConatainer.innerHTML = structureAcceptPopup(ccDonation,communitycenters);

    document.getElementById('cancel').addEventListener('click',cancelFun);

    document.getElementById('popUpBackground').style.display = 'flex';

}

function structureAcceptPopup(ccDonation,communitycenters) {

    const requestedBy = `<div class="form-group"><label class="form-label">Requested By : </label><input class="basic-input-field" id="amount" type="text" value="${communitycenters[ccDonation['toCC']]}" disabled=""></div>`

    const buttons = `<div class='popup-btns'>
    <button class='btn btn-primary' id='confirm' value="${ccDonation['ccDonationID']}">Confirm</button>
    <button class='btn btn-secondary' id='cancel'>Cancel</button>
    </div>`

    return `<div class='split-form'> ${requestedBy} ${requestedBy} </div> ` + buttons;

}

const cancelFun = () => {
    document.querySelector('#popUpContainer').innerHTML = '';
    document.querySelector('#popUpBackground').style.display = 'none';
}