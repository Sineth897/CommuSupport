
class DonationCard {

    constructor(userType = 'donor') {
        this.userType = userType;
    }

    displayDonationCards(div,donations) {
        div.innerHTML = '';
        for(let i = 0; i < donations.length; i++) {
            let donation = donations[i];
            div.appendChild(this.createDonationCard(donation));
        }
    }

    createDonationCard(donation) {
        const donationCard = document.createElement('div');
        donationCard.classList.add('don-del-card');
        donationCard.id = donation['donationID'];
        donationCard.innerHTML = this.#cardHeader(donation) + this.#cardDetails(donation) + this.#cardButtons(donation);
        return donationCard;
    }

    #cardHeader(donation) {
        return `<div class="don-del-header">
            <h4>` + donation['subcategoryName'] + `</h4>
            <p class="don-del-status-cancelled">` + donation['deliveryStatus'] + `</p>
        </div>`;
    }

    #cardDetails(donation) {
        return `<div class="don-del-details">
            <p><strong>Amount:</strong> ` + donation['amount'] + ` </p>
            <p></p>` + (this.userType === 'donor' ? `` : `<p><strong>Donation from:</strong> ${donation['username']}</p>`) +`
            <p><strong>Created:</strong> ` + donation['date'] + `</p>
        </div>`;
    }

    #cardButtons(donation) {
        return `<div class="don-del-btns">
            <button class="don-del-primary">More Details</button>
        </div>`;
    }

}

export default DonationCard;