import { getData } from "../../request.js";
import {PopUp} from "../../popup/popUp.js";

const viewBtns = document.querySelectorAll('a.btn-primary');

for(let i=0; i<viewBtns.length; i++){
    viewBtns[i].addEventListener('click', showPopup)
}

const popup = new PopUp();

async function showPopup(e) {
    const data = {
        choID: e.target.id
    }

    const result = await getData('./communityheadoffices/popup', 'post' ,data);


    popup.clearPopUp();
    popup.setHeader('Community Head Office Details');
    popup.startSplitDiv();
    popup.setBody(result['cho'], ['username','district', 'contactNumber'], ['Username','District', 'Contact Number']);
    popup.setBody(result['cho'], ['address','email'], ['Address','Email']);
    popup.endSplitDiv();
    popup.startSplitDiv();
    popup.insertElement(getCCNames(result['communityCenters']));
    popup.endSplitDiv();
    popup.showPopUp();

    console.log(result);
}

function getCCNames(ccs) {
    let ccDiv = document.createElement('div');
    ccDiv.classList.add('form-group');
    let ccHeading = document.createElement('h5');
    ccHeading.innerHTML = 'Community Centers';
    ccDiv.appendChild(ccHeading)
    for(let i=0; i<ccs.length; i++){
        let cc = document.createElement('input');
        cc.classList.add('basic-input-field');
        cc.value = ccs[i]['city'];
        cc.disabled = true;
        ccDiv.appendChild(cc);
    }
    return ccDiv;
}