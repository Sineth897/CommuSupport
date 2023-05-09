import {getData,getTextData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";
import {displayTable} from "../../components/table.js";

const toggle = new togglePages([
                                    {btnId:'individual',pageId:'individualDoneeDisplay',title:'Individual Donees'},
                                    {btnId:'organization',pageId:'organizationDoneeDisplay',title:'Organization Donees'}]);

let temp =  document.getElementsByClassName('pendingVerification');
let pendingVerifications = {};

for(let i = 0; i < temp.length; i++) {
    let id = temp[i].getElementsByTagName('button')[0].value;
    pendingVerifications[id] = temp[i];
}

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
let popupFunctions = new PopUpFunctions();

let verificationBtns = document.getElementsByClassName('verify');

for(let i = 0; i < verificationBtns.length; i++) {
    verificationBtns[i].addEventListener('click', async function(e) {
        let id = e.target.value;

        let data = await getData('./donee/getdata','post', {doneeID: id});

        // console.log(data);

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

        let documentPath = "/CommuSupport/src/donee/" + data['doneeID'];

        popup.include([{name:"Document front",url:documentPath + 'front.pdf'},{name:"Document back",url:documentPath + 'back.pdf'}]);

        popup.setButtons([{text:'Verify',classes:['btn-primary'],value:data['doneeID'],func:verifyFunc,cancel:true},
            {text:'Reject',classes:['btn-danger'],value:data['doneeID'],func:rejectFunc,cancel:true}]);

        popup.showPopUp();
    });
}

let verifyFunc = async (e) => {
    let btn = e.target;
    let id = e.target.value;
     if(btn.innerHTML === 'Verify') {
         btn.innerHTML = 'Confirm';
         popupFunctions.hideAllElementsWithin(e.target.parentNode);
         e.target.style.display = 'block';
         e.target.nextElementSibling.style.display = 'block';
     }
     else {
         let result = await getData('./donee/verify','post',{doneeID: id});
         if(result['status']) {
                flash.showMessage({type:'success',value:`Donee's verification is marked successfully`},3000);
                pendingVerifications[id].style.display = 'none';
                popup.hidePopUp();
         }
         else {
                console.log(result);
         }
     }
}

let rejectFunc = (e) => {
    let btn = e.target;
    let id = e.target.value;
    if(btn.innerHTML === 'Reject') {
        btn.innerHTML = 'Confirm';
        popupFunctions.hideAllElementsWithin(e.target.parentNode);
        e.target.style.display = 'block';
        e.target.nextElementSibling.style.display = 'block';
    }
    else {
        console.log(id);
    }
}

const individualDoneeDisplay = document.getElementById('individualDoneeDisplay');
const organizationDoneeDisplay = document.getElementById('organizationDoneeDisplay');

let filterOptions = document.getElementById('filterOptions');
let sortOptions = document.getElementById('sortOptions');

document.getElementById('filter').addEventListener('click', function(e) {

    if(e.target !== this) {
        return;
    }

    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
    sortOptions.style.display = 'none';
});

document.getElementById('sort').addEventListener('click', function(e) {

    if(e.target !== this) {
        return;
    }

    if(sortOptions.style.display === 'block') {
        sortOptions.style.display = 'none';
    } else {
        sortOptions.style.display = 'block';
    }
    filterOptions.style.display = 'none';
});

const filterBtn = document.getElementById('filterBtn');
const sortBtn = document.getElementById('sortBtn');
const searchBtn = document.getElementById('searchBtn');

const verificationStatusFilter = document.getElementById('verificationStatusFilter');

const registeredDateSort = document.getElementById('registeredDateSort');

const searchInput = document.getElementById('searchInput');

filterBtn.addEventListener('click', async function(e) {

    let filters = {};

    if(verificationStatusFilter.value) {
        filters['verificationStatus'] = verificationStatusFilter.value;
    }

    let sort = {DESC: []};

    if(registeredDateSort.checked) {
        sort['DESC'].push('registeredDate');
    }

    let search = searchInput.value;

    let data = await getData('./donees/filter','post',{filters,sort,search});

    console.log(data);

    if(!data['status']) {
        flash.showMessage({type:'error',value:data['msg']},3000);
        return;
    }

    const individualDoneeTableData = {
        headings: ["First Name","Last Name","Is Verified","Contact Number","Email",],
        keys: ["fname","lname",['verificationStatus','bool',['No','Yes']],"contactNumber","email",['','View','#',[],'doneeID']],
        data: data['individualDonees'],
    }

    const organizationDoneeTableData = {
        headings: ["Organization Name","Representative","Is Verified","Contact Number","Email",],
        keys: ["organizationName","representative",['verificationStatus','bool',['No','Yes']],"contactNumber","email",['','View','#',[],'doneeID']],
        data: data['organizationDonees'],
    }

    displayTable(individualDoneeDisplay,individualDoneeTableData);
    displayTable(organizationDoneeDisplay,organizationDoneeTableData);

    filterOptions.style.display = 'none';
    sortOptions.style.display = 'none';

    toggle.checkNoData();

    let viewBtns = document.querySelectorAll('.view');

    for(let i=0;i<viewBtns.length;i++) {
        viewBtns[i].addEventListener('click', showDoneePopup);
    }

});

sortBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});

searchBtn.addEventListener('click', async function(e) {
    filterBtn.click();
});

let viewBtns = document.querySelectorAll('.view');

for(let i=0;i<viewBtns.length;i++) {
    viewBtns[i].addEventListener('click', showDoneePopup);
}

async function showDoneePopup(e) {
    const doneeID = e.target.id;
    const doneeType = toggle.getActivePage()['btn'].id;

    // console.log(doneeID,doneeType);

    const result = await getData('./donee/popup','post',{doneeID:doneeID,doneeType:doneeType});

    console.log(result);

    if(!result['status']) {
        flash.showMessage({type:'error',value:result['msg']},3000);
        return;
    }

    popup.clearPopUp();
    popup.setHeader("Donor Details");

    popup.setSubHeading('User Account Details');
    popup.startSplitDiv();
    popup.setBody(result['donee'], ['username', 'registeredDate'], ['Username', 'Registered Date']);
    popup.setBody(result['donee'], ['lockedStatus','mobileVerification'], [['Account Locked','bool'],['Mobile Verified','bool']]);
    popup.endSplitDiv();


    if(doneeType === 'organization') {
        popup.setSubHeading('Organization Details');
        popup.startSplitDiv();
        popup.setBody(result['donee'], ['organizationName', 'contactNumber', 'regNo'], ['Organization Name', 'Contact Number', 'Registration Number']);
        popup.setBody(result['donee'], ['representative', 'representativeContact', 'email'], ['Representive', 'Representative contact', 'Email']);
        popup.endSplitDiv();
    } else {
        popup.setSubHeading('Personal Details');
        popup.startSplitDiv();
        popup.setBody(result['donee'], ['fname','verificationStatus', 'contactNumber',], ['First Name', ['Verified Donee','bool'],'Contact Number']);
        popup.setBody(result['donee'],['lname','age','email'],['Last Name','Age','Email']);
        popup.endSplitDiv();

    }


    popup.showPopUp();
}
