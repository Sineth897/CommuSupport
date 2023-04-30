
// class to create cc donation cards when data is received through fetch request
class CCDonationCard {

    //  static variable to hold the type of card
    static cardType = 'posted';

    // static variable to hold whether the current card is accepted or not
    static accepted = false;

    // static variable to hold the community center of the currently logged in logistic officer
    static ccID = '';

    // static method to append cards of defined type to provided div
    // type can be either 'posted', 'ongoing', or 'completed'
    static displayCards(div, data,ccID, type = 'posted') {

        // set the card type
        CCDonationCard.cardType = type;

        // set the ccID
        CCDonationCard.ccID = ccID;

        //clear the div
        div.innerHTML = '';

        // loop through data and create card for each donation
        data.forEach(donation => {

            // change the static variable for each card
            CCDonationCard.accepted = donation['fromCC'];

            // append card to provided div
            div.appendChild(CCDonationCard.getCCDonationCard(donation));
        });

    }

    // static method to create a card for a single donation
    static getCCDonationCard(donation) {

        // create card div, assign class and id
        const card = document.createElement('div');
        card.classList.add('CC-donation-card');
        card.id = donation['ccDonationID'];

        // structure cars => header, details and buttons
        card.innerHTML = CCDonationCard.getCCDonationCardHeader(donation) + CCDonationCard.getCCDonationCardDetails(donation) + CCDonationCard.getCCDonationCardButtons(donation) ;

        // return the created card
        return card;
    }

    // get relevant header for card
    static getCCDonationCardHeader(donation) {

        // switch statement to determine header based on card type
        switch (CCDonationCard.cardType) {

            case 'posted':
                return `<div class="CC-donation-card-header"> 
                            <h4> ${donation['subcategoryName']} </h4> 
                        </div>`;

            case 'ongoing':

                // if the donation is accepted, display accepted, else display not accepted
                if(CCDonationCard.accepted) {
                    return `<div class="CC-donation-header"> 
                                <h4> ${donation['subcategoryName']} </h4> 
                                <strong class="color-secondary-font-point9rem"> Accepted </strong> 
                            </div>`;
                }
                else {
                    return `<div class="CC-donation-header"> 
                                <h4> ${donation['subcategoryName']} </h4> 
                                <strong class="color-danger-font-point9rem"> Not accepted </strong> 
                            </div>`;
                }

            case 'completed':
                return `<div class="CC-donation-header"> 
                            <h4> ${donation['subcategoryName']} </h4> 
                            <p class="font-point8rem"> ${donation['completedDate']} </p> 
                        </div>`;
        }

    }

    // get relevant details for card
    static getCCDonationCardDetails(donation) {

        // switch statement to determine details based on card type
        switch (CCDonationCard.cardType) {

            case 'posted':
                return `<div class="CC-donation-details"> 
                            <p> <span> Amount: </span> ${donation['amount']} </p> 
                            <p> <span> Posted By: </span> ${donation['toCity']} </p> 
                         </div>`

            // both other cases have same details, so default case is used
            default:

                // if logistic officer is the one who posted the donation, display accepted by, else display posted by
                if(CCDonationCard.ccID === donation['toCC']) {
                    return `<div class="CC-donation-details"> 
                                <p> <span> Amount: </span> ${donation['amount']} </p> 
                                <p> <span> Accepted By: </span> ${donation['fromCity']} </p> 
                            </div>`
                }
                else {
                    return `<div class="CC-donation-details">
                            <p> <span> Amount: </span> ${donation['amount']} </p>
                            <p> <span> Posted By: </span> ${donation['toCity']} </p>
                        </div>`
                }

        }

    }

    static getCCDonationCardButtons(donation) {

        // switch statement to determine buttons based on card type
        switch (CCDonationCard.cardType) {

            case 'posted':
                return `<div class="CC-donation-btns"> 
                            <button class='CC-donation-primary accept'>Accept</button>
                        </div>`;

            case 'ongoing':

                // if the donation is accepted, set view btn , otherwise show the details of the donation
                if(CCDonationCard.accepted) {
                    return `<div class="CC-donation-btns"> 
                                <button class='CC-donation-primary view'>View</button>
                            </div>`;
                }
                else {
                    return `<div class="CC-donation-note"> 
                                <p> ${donation['notes']} </p>
                            </div>`;
                }

            case 'completed':
                return `<div class="CC-donation-btns"> 
                            <button class='CC-donation-primary view'>View</button>
                        </div>`;
        }

    }

}

export default CCDonationCard;