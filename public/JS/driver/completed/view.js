import { getData, getTextData } from "../../request.js";

function assignViewDeliveryToDeliveryCards() {

    const deliveryCards = document.querySelectorAll(".view-completed-delivery");

    for(let i = 0, length = deliveryCards.length; i < length; i++) {
        deliveryCards[i].addEventListener("click", showPopup);
    }

}

assignViewDeliveryToDeliveryCards();

function getParentWithID(element) {

        while(element.id === "") {
            element = element.parentElement;
        }

        return element;
}

async function showPopup(e) {

    const parent = getParentWithID(e.target);

    const result = await getData('./popup','post', {subdeliveryID: parent.id.split(",")[0]});

    console.log(result);
}