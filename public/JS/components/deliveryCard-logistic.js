
// delivery card component to show filtered data to the logistic
class DeliveryCardLogistic {

    // static variables to store addresses of the destinations
    static destinations = [];

    // static variables to store subcategory names
    static subcategories = [];

    constructor(destinations,subcategories) {

        // assign destinations and subcategories to static variables
        DeliveryCardLogistic.destinations = destinations;
        DeliveryCardLogistic.subcategories = subcategories;

        // get the last part of the address and assign it to the static variable
        const keys = Object.keys(DeliveryCardLogistic.destinations);

        keys.forEach(key => {
            const address = DeliveryCardLogistic.destinations[key].split(',');
            DeliveryCardLogistic.destinations[key] = address[address.length - 1];
        });

    }

    showDeliveryCards(div,deliveries,type) {

        // traverse through the deliveries and create delivery cards
        deliveries.forEach(delivery => {

            // assign start and end city to the delivery, and the name of the subcategory
            delivery['startCity'] = DeliveryCardLogistic.destinations[delivery['start']];
            delivery['endCity'] = DeliveryCardLogistic.destinations[delivery['end']];
            delivery['subcategory'] = DeliveryCardLogistic.subcategories[delivery['item']];

            // append the delivery card to the div
            div.append(this.getDeliveryCard(delivery,type));

        });

    }

    // get the delivery card
    getDeliveryCard(delivery,type) {

        // create a div element and assign the class and id
        const deliveryCard = document.createElement('div');
        deliveryCard.classList.add('delivery-card');
        deliveryCard.id = type === 'directDonations' ? delivery['donationID'] : type === 'ccDonations' ? delivery['ccDonationID'] : delivery['acceptedID'];

        // structure the delivery card
        deliveryCard.innerHTML = this.getDeliveryCardType(type) + this.getDeliveryCardHeader(delivery,type) + this.getDeliveryCardDetails(delivery,type) + this.getDeliveryCardBtns(delivery,type) ;

        // return the delivery card
        return deliveryCard;

    }

    // get the delivery card type
    getDeliveryCardType(type) {

        // return the delivery card type based on the type
        switch(type) {
            case 'directDonations':
                return  `<div class='delivery-card-type'><h4> Direct Donation </h4></div>`;
            case 'ccDonations':
                return `<div class='delivery-card-type'><h4> CC Donation </h4></div>`;
            case 'acceptedRequests':
                return `<div class='delivery-card-type'><h4> Accepted Request </h4></div>`;
        }

    }

    // get the delivery card header
    getDeliveryCardHeader(delivery,type) {

        // return the delivery card header
        return `<div class='delivery-card-header'>
                    <h4> ${delivery['subcategory']} </h4>
                    <p class='log-del-status-cancelled'> ${type === 'acceptedRequests' ? delivery['deliveryStatus'] : delivery['status']} </p>
                </div>`;

    }

    // get details of the delivery
    getDeliveryCardDetails(delivery,type) {

        // because array key different in the accepted requests from other two
        const createdDate = type === 'directDonations' ? delivery['date'] : type === 'ccDonations' ? delivery['date'] : delivery['approvedDate'];

        // return details of the delivery
        return `<div class="log-del-details">
                    <p><strong>Start: </strong> ${delivery['startCity']} </p>
                    <p><strong>Dest: </strong> ${delivery['endCity']}</p>
                    <p><strong>Created: </strong> ${createdDate} </p>
                </div>`;

    }

    // get the view btn of the delivery card
    getDeliveryCardBtns(delivery,type) {

        return `<div class="log-del-btns">
                    <button class="log-del-primary view-btn" id="${delivery['subdeliveryID']}" value="${type}"> More Details </button>
                </div>`;

    }

}

export default DeliveryCardLogistic;