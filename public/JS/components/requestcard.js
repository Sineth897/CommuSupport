

class Requestcard {

    static btnDetails = {
        "Accept" : `<button class='rq-btn btn-primary %s' value='%s'>Accept</button>`,
        "Reject" : `<button class='rq-btn btn-danger %s' value='%s'>Reject</button>`,
        "Approve" : `<button class='rq-btn btn-primary %s' value='%s'>Approve</button>`,
        "View" : `<button class='rq-btn btn-primary %s' value='%s'>View</button>`,
    }


    static showCards(requests,container,btns,accepted = false) {
        this.accepted = accepted;
        requests.forEach( (request) => {
            container.append(Requestcard.getCard(request,btns));
        } );
    }

    static getCard(request,btns) {
        const card = document.createElement('div');
        card.classList.add('rq-card');
        card.id = this.accepted ? request['acceptedID'] : request['requestID'];
        card.innerHTML = `<div class='rq-card-header'>
                <h1>${request['subcategoryName']}</h1>` +
                (this.accepted ? `<div class='rq-delivery-status'><strong>Delivery : </strong><p>${request['deliveryStatus']}</p></div>` : ``) +
                `</div><div class='rq-category'>
                <p>${request['categoryName']}</p></div>
                <div class='rq-description'>
                <p>${request['notes']}</p></div>` +
                (this.accepted ? `<p class='rq-accepted-date'><strong>Accepted On : </strong> ${request['acceptedDate']} </p>`: ``) + Requestcard.getButtons(btns) ;
        return card;
    }

    static getButtons(btns) {
        if(btns === undefined) return ``;
        this.btns = ``;
        btns.forEach( (btn) => {
          this.btns += this.btnDetails[btn[0]].replace('%s',btn[1]);
        });
        return `<div class='rq-btns'>` + this.btns + `</div>`;
    }

}

export default Requestcard;