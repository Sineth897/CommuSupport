import {getData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";

let toggle = new togglePages([{btnId:'individual',pageId:'individualDoneeDisplay'},{btnId:'organization',pageId:'organizationDoneeDisplay'}]);

function toggleHidden(element) {
    if(element.style.display === "none") {
        show(element);
    } else {
        hide(element);
    }
}

function hide(element) {
    element.style.display = "none";
}

function show(element) {
    element.style.display = "block";
}

let popup = new PopUp();

let verificationBtns = document.getElementsByClassName('verify');

for(let i = 0; i < verificationBtns.length; i++) {
    verificationBtns[i].addEventListener('click', async function(e) {
        let id = e.target.value;

        popup.clearPopUp();
        popup.include('../src/donee/' + id + 'front.pdf');
        popup.showPopUp();
    });
}

