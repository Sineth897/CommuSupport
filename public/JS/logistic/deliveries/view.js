import togglePages from "../../togglePages.js";
import flash from "../../flashmessages/flash.js";
import { getData } from "../../request.js";

let toggle = new togglePages([{btnId:'pending',pageId:'pendingDeliveries'},{btnId:'completed',pageId:'completedDeliveries'}]);

let deliveryCards = document.querySelectorAll('.delivery-card');
let assignBtns = {};

for(let i=0;i<deliveryCards.length;i++) {
    let assignBtn = deliveryCards[i].querySelector('.assign-btn');
    assignBtn.addEventListener('click', showDeliveryPopUp)
    assignBtns[assignBtn.id] = deliveryCards[i];
}
async  function showDeliveryPopUp(e) {
    console.log(assignBtns[e.target.id].id)
    const data = {
        deliveryID: e.target.id,
        related: assignBtns[e.target.id].id
    };
    const result = await getData('./delivery/popup', 'POST', { data: { deliveryID: e.target.id } });
    console.log(result);
}