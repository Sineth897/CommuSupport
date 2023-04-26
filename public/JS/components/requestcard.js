

class Requestcard {

    static btnDetails = {
        "Accept" : `<button class='rq-btn btn-primary %s' value='%s'>Accept</button>`,
        "Reject" : `<button class='rq-btn btn-danger %s' value='%s'>Reject</button>`,
        "Approve" : `<button class='rq-btn btn-primary %s' value='%s'>Approve</button>`,
        "View" : `<button class='rq-btn btn-primary %s' value='%s'>View</button>`,
    }


    static showCards(requests,container,btns) {
        requests.forEach( (request) => {
            container.append(Requestcard.getCard(request,btns));
        } );
    }

    static getCard(request,btns) {
        const card = document.createElement('div');
        card.classList.add('rq-card');
        card.id = request['requestID'];
        card.innerHTML = `<div class='rq-card-header'>
                <h1>${request['subcategoryName']}</h1></div>
                <div class='rq-category'>
                <p>${request['categoryName']}</p></div>
                <div class='rq-card-body'>
                <p>${request['notes']}</p></div>` + Requestcard.getButtons(btns);
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