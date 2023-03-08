import {getData} from "../../request.js";
import {PopUp} from "../../popup/popUp.js";
import {PopUpFunctions} from "../../popup/popupFunctions.js";
import togglePages from "../../togglePages.js";

let toggle = new togglePages([{btnId:'pending',pageId:'pendingRequests'},{btnId:'posted',pageId:'postedRequests'},{btnId:'history',pageId:'completedRequests'}],'grid');

let requests = document.querySelectorAll('.pendingRequestView');
requests = Array.from(requests);

for(let i = 0; i < requests.length; i++) {
    requests[i].addEventListener('click', (e) => showPendingReqPopUp(e));
}


let popUpRequest = new PopUp();

async function showPendingReqPopUp(e) {

    let request = await getData('./requests/popup', 'POST', {"r.requestID": e.target.value});
    console.log(request);

    let reqDetails = request['requestDetails'];
    let donee = request['donee'];


    popUpRequest.clearPopUp();

    popUpRequest.insertHeading('Posted by');
    popUpRequest.startSplitDiv();
    if(donee['type'] === 'Individual') {
        popUpRequest.setBody(donee,['name','contactNumber'],['Name','Contact Number']);
        popUpRequest.setBody(donee,['address','registeredDate'],['Address','Registered On']);
    } else {
        popUpRequest.setBody(donee,['organizationName','contactNumber','representativeName'],['Organization Name','Contact Number','Representative Name']);
        popUpRequest.setBody(donee,['address','registeredDate','representativeContact'],['Address','Registered On','Representative Contact']);
        if(donee['capacity']) {
            popUpRequest.setBody(donee,['capacity'],['Dependants']);
        }
    }
    popUpRequest.endSplitDiv();
    popUpRequest.insertHeading('Request Details');
    popUpRequest.startSplitDiv();
    popUpRequest.setBody(reqDetails,['requestID','postedDate','subcategoryName'],['ID','Date Posted','Item']);
    popUpRequest.setBody(reqDetails,['address','urgency','amount'],['Address','Urgency','Amount']);
    popUpRequest.setBody(reqDetails,['notes'],['Additional Notes']);
    popUpRequest.endSplitDiv();
    popUpRequest.setButtons([{text:'Approve',classes:['btn-primary'],value:reqDetails['requestID'],func:approveFun,cancel:true},
        {text:'Reject',classes:['btn-danger'],value:reqDetails['requestID'],func:rejectFun,cancel:true}]);
    popUpRequest.showPopUp();
}

let popUpFunctions = new PopUpFunctions();


let approveFun = async (e) => {
    let btn = e.target;

    if(btn.innerHTML === 'Approve') {
        btn.innerHTML = 'Confirm';
        popUpFunctions.hideAllElementsWithin(btn.parentNode);
        btn.style.display = 'block';
        btn.nextElementSibling.style.display = 'block';
    }
    else {
        let requestData = {requestID:btn.value};
        let result = await getData('./request/approve', 'POST', {do:'approve',data:requestData});
        if(result['status']) {
            console.log('updated');
        } else {
            console.log(result);
        }
        popUpRequest.hidePopUp();
    }
}

let observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if(mutation.type === 'attributes' && mutation.attributeName === 'style' && mutation.target.style.display === 'none') {
            document.getElementById('rejectedReason').remove();
        }
    });
});

let rejectFun = async (e) => {
    if(e.target.innerText === 'Reject') {
        e.target.innerText = 'Confirm';
        popUpFunctions.hideAllElementsWithin(e.target.parentNode);
        e.target.style.display = 'block';
        e.target.nextElementSibling.style.display = 'block';
        let parent = Object.assign(document.createElement('div'),{className:'popup-details',style:'width: 100%;margin: 0 auto 5px',rows:'5',id:'rejectedReason'});
        let reasonDiv = Object.assign(document.createElement('div'),{className:'form-group'});
        let reasonLabel = Object.assign(document.createElement('label'),{innerText:'Reason for rejection',className:'form-label'});
        let reasonInput = Object.assign(document.createElement('textarea'),{className:'basic-text-area',name:'rejectedReason'});
        reasonDiv.append(reasonLabel,reasonInput);
        parent.append(reasonDiv);
        e.target.parentNode.parentNode.insertBefore(parent,e.target.parentNode);

        observer.observe(e.target.nextElementSibling,{attributes:true,attributeFilter:['style']});

    }
    else {
        let requestData = {requestID: e.target.value,rejectedReason: document.getElementById('rejectedReason').querySelector('textarea').value};
        let result = await getData('./request/approve', 'POST', {do:'reject',data:requestData});
        if(result['status']) {
            console.log('updated');
        }
        else {
            console.log(result);
        }
        popUpRequest.hidePopUp();
    }
}
