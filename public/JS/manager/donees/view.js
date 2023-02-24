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

        let data = await getData('./donee/getdata','post', {doneeID: id});

        console.log(data);

        popup.clearPopUp();
        popup.setHeader('Donee Details')
        popup.startSplitDiv();
        if(data['type'] === 'Individual') {
            popup.setBody(data,['fname','age','registeredDate'],['First name','Age','Registered on']);
            popup.setBody(data,['lname','NIC','mobileVerification'],['Last name',"NIC",['Mobile verified','bool']]);
        }
        else {
            popup.setBody(data,['organizationName','representative','registeredDate'],['Name','Representative','Registered on']);
            popup.setBody(data,['regNo','representativeContact','mobileVerification'],['Registration Number',"Representative Contact",['Mobile verified','bool']]);
        }
        popup.endSplitDiv();
        popup.startSplitDiv();
        popup.setBody(data,['email','address'],['Email','Address']);
        popup.setBody(data,['contactNumber'],['Contact Number']);
        popup.endSplitDiv();


        popup.showPopUp();
    });
}

