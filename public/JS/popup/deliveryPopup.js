
class DeliveryPopUp  {

    static closeBtn = `<div id='closeBtnDiv' class='close'> <i class='material-icons'>close</i> </div>`;
    static deliveryStage =  ["1st","2nd","3rd"];

    constructor() {
        this.background = document.getElementById('popUpBackground');
        // this.background.className = 'background';
        this.popup = document.getElementById('popUpContainer');
        this.popup.className = 'delivery-popup';
    }

    async showDeliveryPopUp(data,type) {
        this.popup.innerHTML = DeliveryPopUp.closeBtn + `<div class="delivery-map">
            <h4>Route of the current stage</h4>
            <div id="map"></div>
        </div>`

            + DeliveryPopUp.getDeliveryAddress(data)

                + DeliveryPopUp.getDeliveryContent(data)

                +

        `<div class="driver-selection">
            <h4>Select a Driver</h4>
            <p>Drivers are recommended as best match first.</p>
            <p class="error" id="driverSelectionError"></p>
            <div class="driver-scroller" id="driverScroller">
            </div>
            <div class="driver-selection-btns">
                <a class="driver-assign-btn" id='` + data['subdeliveryID'] + `,` + data[this.#getProcessIDkey(type)] + `,` + type + `'>Assign</a>
        </div>`;
        this.background.style.display = 'flex';

        document.getElementById('closeBtnDiv').addEventListener('click', () => {
            this.background.style.display = 'none';
            this.popup.innerHTML = '';
        });

        return this.popup;
    }

    static getDeliveryAddress(data) {
        return `<div class="delivery-details">
            <div class="delivery-id">
                <h2>` + data['subdeliveryID'].substring(18,23) + `</h2>
                <p>Delivery ID</p>
            </div>
            <div class="delivery-distance">
                <h2 id="distance">Long</h2>
                <p>Distance</p>
            </div>
            <div class="delivery-stage">
                <h2>` + DeliveryPopUp.deliveryStage[data['deliveryStage'] - 1] +`</h2>
                <p>Stage Delivery</p>
            </div>
        </div>`
    }

    static getDeliveryContent(data) {
        return `<div class="delivery-details">
            <div class="delivery-address pickup">
                <h4>
                    Pickup Address
                </h4>
                <p>
                    ` + data["startAddress"] + `
                </p>
            </div>
            <div class="delivery-address destination">
                <h4>
                    Delivery Address
                </h4>
                <p>
                    ` + data["endAddress"] + `
                </p>
            </div>
            <div class="delivery-content">
                <h4>
                    Content
                </h4>
                <p>
                `+ data['subcategoryName'] + `
                </p>
                <p>` + data['amount'] +`</p>
                
            </div>
        </div>`
    }

    static getDriverCard(data) {
        const driverDiv = Object.assign(document.createElement('div'), {className: 'driver', id: data['employeeID']});
        driverDiv.innerHTML = `<h4>` + data['name'] +`</h4>
            <div class="driver-details" ><p>Distance Preference </p>
                <p>
                    ` + data['preference'] + `
                </p>
                <p>Assigned Deliveries </p><p> ` + data['active'] + ` </p></div>`;

        driverDiv.addEventListener('click', (e) => {
            let parent = e.target.parentNode;
            while(parent.id !== 'driverScroller') {
                parent = parent.parentNode;
            }
            const children = parent.querySelectorAll('div');
            for(let i = 0; i < children.length; i++) {
                children[i].classList.remove('selected');
            }
            let driver = e.target;
            while(driver.className !== 'driver') {
                driver = driver.parentNode;
            }
            driver.classList.add('selected');
        });

        return driverDiv;
    }

    #getProcessIDkey(str) {
        switch (str) {
            case 'acceptedRequests':
                return 'acceptedID';
            case 'directDonations':
                return 'donationID';
            case 'ccDonations':
                return 'ccDonationID';
            default:
                return '';
        }

    }

    static closePopUp() {
        document.getElementById('popUpBackground').style.display = 'none';
        document.getElementById('popUpContainer').innerHTML = '';
    }


    static showRouteDiv(subdeliveryID) {
        const popupContainer = document.getElementById('popUpContainer');
        popupContainer.className = 'delivery-popup';
        popupContainer.innerHTML = DeliveryPopUp.closeBtn + `<div class="delivery-map">
            <h4>Route of the current stage</h4>
            <div id="map"></div>
            </div>`
            + `<div class="delivery-details">
            <div class="delivery-id">
                <h2>` + subdeliveryID.substring(18,23) + `</h2>
                <p>Delivery ID</p>
            </div>
            <div class="delivery-distance">
                <h2 id="distance">Long</h2>
                <p>Distance</p>
            </div>
            <div class="delivery-stage">
                <h2 id='duration'>` + `deliveryStage` +`</h2>
                <p>Estimated Time</p>
            </div>
        </div>`;
        document.getElementById('popUpBackground').style.display = 'flex';
        document.getElementById('closeBtnDiv').addEventListener('click', () => {
            document.getElementById('popUpBackground').style.display = 'none';
            document.getElementById('popUpContainer').innerHTML = '';
        });

        return popupContainer;
    }
}

export default DeliveryPopUp;