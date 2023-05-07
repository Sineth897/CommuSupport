
// to show completed deliveries or assigned deliveries
class DriverDeliveryCard {

    static showDeliveries(div, deliveries) {
        div.innerHTML = '';

        deliveries.forEach(delivery => {

            if (delivery['status'] === 'Completed') {
                div.appendChild(this.createCompletedDeliveryCard(delivery));
            }
            else {
                div.appendChild(this.createDeliveryCard(delivery));
            }

        });
    }

    static createCompletedDeliveryCard(delivery) {

        // create a div with class 'delivery-del-card' and add attributes to it
        const deliveryCard = document.createElement('div');
        deliveryCard.classList.add('driver-del-card');
        deliveryCard.id = delivery['subdeliveryID'];

        //structure card
        deliveryCard.innerHTML = `
            <div class="card-column subcategory"><strong>Sub Category</strong><p>${delivery['item']}</p></div>
            <div class="card-column pickupaddress"><strong>Pick up Address</strong><p>${delivery['startAddress']}</p></div>
            <div class="card-column deliveryaddress"><strong>Drop Off</strong><p>${delivery['endAddress']}</p></div>
            <div class="card-column assigneddate"><strong>Created Date</strong><p>${delivery['createdDate']}</p></div>
            <div class="card-column assigneddate"><strong>Completed Date</strong><p>${delivery['completedDate']}</p></div>
            <div class="card-column route-complete-btns"><a class='del-finish view-completed-delivery' href='#'>View details</a></div>`;

        return deliveryCard;
    }

    static createDeliveryCard(delivery) {

        // create a div with class 'delivery-del-card' and add attributes to it
        const deliveryCard = document.createElement('div');
        deliveryCard.classList.add('driver-del-card');
        deliveryCard.id = delivery['subdeliveryID'];

        //structure card
        deliveryCard.innerHTML = `
            <div class="card-column subcategory"><strong>Sub Category</strong><p>${delivery['item']}</p></div>
            <div class="card-column pickupaddress"><strong>Pick up Address</strong><p>${delivery['startAddress']}</p></div>
            <div class="card-column deliveryaddress"><strong>Drop Off</strong><p>${delivery['endAddress']}</p></div>
            <div class="card-column assigneddate"><strong>Created Date</strong><p>${delivery['createdDate']}</p></div>
            <div class='card-column route-complete-btns'>
            <a class='del-route' href=#'>Route</a>
            <a class='del-finish' href='#'>Finish</a></div>
            <div class='card-column delivery-btns'>
            <a class='del-reassign' href='#'>${delivery['status'] === 'Ongoing' ? 'Request to Re-Assign' : 'Cancel Re-assign request'}</a></div>`;

        return deliveryCard;
    }



}

export default DriverDeliveryCard;