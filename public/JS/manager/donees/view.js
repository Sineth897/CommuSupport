import {getData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";

let individual = document.getElementById('individual');
let organizations = document.getElementById('organization');

let individualDoneeDisplay = document.getElementById('individualDoneeDisplay');
let organizationDoneeDisplay = document.getElementById('organizationDoneeDisplay');

individual.addEventListener('click', function() {
    individual.classList.add('active-heading-page');
    organizations.classList.remove('active-heading-page');
    show(individualDoneeDisplay);
    hide(organizationDoneeDisplay);
});

organizations.addEventListener('click', function() {
    individual.classList.remove('active-heading-page');
    organizations.classList.add('active-heading-page');
   show(organizationDoneeDisplay);
   hide(individualDoneeDisplay);
});

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

