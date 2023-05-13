import { getData } from "../../request.js";
import {PopUp} from "../../popup/popUp.js";

const viewBtns = document.querySelectorAll('.view');

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
    const ccUl = document.createElement('ul');
    ccDiv.appendChild(ccUl);
    for(let i=0; i<ccs.length; i++){
        let cc = document.createElement('li');
        cc.style.listStyleType = 'none';
        cc.style.display = 'flex';
        // cc.classList.add('basic-input-field');
        cc.innerHTML = ccs[i]['city'];
        cc.disabled = true;

        const viewBtn = document.createElement('i');
        viewBtn.classList.add('material-icons');
        viewBtn.innerHTML = 'visibility';
        cc.append(viewBtn);

        ccUl.appendChild(cc);
    }
    return ccDiv;
}