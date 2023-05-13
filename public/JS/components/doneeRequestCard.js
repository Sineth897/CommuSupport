
// class to show request cards to donees
class DoneeRequestCard {

    static showPostedRequestCards(div,requests) {

        // clear the div
        div.innerHTML = '';

        for (let i = 0; i < requests.length; i++) {

            // append the card to the div
            div.append(DoneeRequestCard.getPostedRequestCard(requests[i]));

        }

    }

    static getPostedRequestCard(request) {

        // create the card
        const card = document.createElement('div');
        card.className = 'posted-rq-card';
        card.id = request['requestID'];

        card.innerHTML = DoneeRequestCard.postedRequestCardInner(request);

        // return the card
        return card;

    }

    static postedRequestCardInner(request) {

        // structure of the card
        return `<div class="posted-rq-card-header">
                    <h2>${request['subcategoryName']}</h2>
                    <p class="posted-rq-card-approval"> <strong>Approval : </strong> ${request['approval']} </p>
                </div>
                <div class="posted-rq-card-info">
                    <p><strong>Amount : </strong> ${request['amount']} </p>
                    <p><strong>Posted date : </strong> ${request['postedDate']} </p>
                </div>
                <div class="posted-rq-card-info">
                    <p><strong>Urgency : </strong> ${request['urgency']} </p>
                    <p><strong>Visible until : </strong> ${request['expDate']} </p>
                </div>
                <div class="posted-rq-card-info  description">
                    <p>${request['notes'] ? request['notes'] : `<p style='color: var(--danger-color)'>You have not added any note</p>`}</p>
                </div>
                <div class="posted-rq-card-info accepted-info">
                    ${DoneeRequestCard.getAcceptedInfo(request)}
                </div>`;
    }

    static getAcceptedInfo(request) {

        // if the request is not approved yet
        if(request['approval'] === 'Pending') {
            return `<p><strong> Your request haven't been approved by the manager yet! </strong> </p>`;
        }
        // if the request is approved but no one has accepted it yet
        else if(parseInt(request['users']) === 0 ) {
            return `<p><strong> No user have accepted your request yet </strong> </p>`;
        }
        // if the request is approved and someone has accepted it
        else {
            return `<p><strong> ${request['users']} users </strong>have accepted ${request['acceptedAmount']}. Check under accepted page for more info </p>`;
        }

    }

    static showAcceptedRequestCards(div,requests) {

            // clear the div
            div.innerHTML = '';

            for (let i = 0; i < requests.length; i++) {

                // append the card to the div
                div.append(DoneeRequestCard.getAcceptedRequestCard(requests[i]));

            }

    }

    static getAcceptedRequestCard(request) {

            // create the card
            const card = document.createElement('div');
            card.className = 'rq-card';
            card.id = request['acceptedID'];

            card.innerHTML = DoneeRequestCard.acceptedRequestCardInner(request);

            // return the card
            return card;

    }

    static acceptedRequestCardInner(request) {

        // structure of the card
        return `<div class="rq-card-header">
                    <h2>${request['subcategoryName']}</h2>
                    <div class="rq-delivery-status">
                        <strong>Delivery : </strong><p>${request['deliveryStatus']}</p>
                    </div>
                </div>
                <div class="rq-category">
                    <p>${request['categoryName']}</p>
                </div>
                <div class="rq-accepted-details">
                    <p><strong>Accepted amount : </strong> ${request['amount']} </p>
                    <p><strong>Accepted Date : </strong> ${request['acceptedDate']} </p>
                </div>
                <div class="rq-btns">
                    <button class="rq-btn btn-primary viewRequest" value="${request['requestID']}">View</button>
                </div>`

    }

    static showCompletedRequestCards(div,requests) {

            // clear the div
            div.innerHTML = '';

            for (let i = 0; i < requests.length; i++) {

                // append the card to the div
                div.append(DoneeRequestCard.getCompletedRequestCard(requests[i]));

            }

    }

    static getCompletedRequestCard(request) {

                // create the card
                const card = document.createElement('div');
                card.className = 'rq-card';
                card.id = request['acceptedID'];

                card.innerHTML = DoneeRequestCard.completedRequestCardInner(request);

                // return the card
                return card;

    }

    static completedRequestCardInner(request) {
        return `<div class="rq-card-header">
                    <h1>${request['subcategoryName']}</h1>
                    <div class="rq-delivery-status">
                        <strong>Delivery : </strong><p>${request['deliveryStatus']}</p>
                    </div>
                </div>
                <div class="rq-category">
                    <p>${request['categoryName']}</p>
                </div>
                <div class="rq-description">
                    <p>${request['notes']}</p>
                </div>
                <div class="rq-btns">
                    <button class="rq-btn btn-primary viewActiveRequest" value="${request['requestID']}">View</button>
                </div>
                <p class="rq-accepted-date"><strong>${request['users']} users </strong> donated</p>`
    }


}

export default DoneeRequestCard;