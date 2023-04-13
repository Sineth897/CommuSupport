
let eventIcons = [];
let locationIcon = "/CommuSupport/public/src/icons/event/location.svg";
let participationIcon = "/CommuSupport/public/src/icons/event/participants.svg";

function displayEventcards(eventsDiv,array) {
    eventIcons = array['icons'];
    eventsDiv.innerHTML = '';
    if(array['event'].length === 0) {
        let heading = document.createElement('h2')
        heading.innerHTML = "There no event to display"
        eventsDiv.appendChild(heading);
    }
    else {
        array['event'].forEach( function (event) {
            eventsDiv.append(eventCard(event));
        });
    }
}

function eventCard(event) {
    let cardUpper = getDiv(['event-card-header']);
    let cardMiddle = getDiv(['event-title']);
    let cardLower = getDiv(['event-details']);
    cardUpper.append(getEventIcon(event['eventCategoryID']),getParticipationCount(event));
    cardMiddle.append(getTheme(event['theme']));
    cardLower.append(getEventLocation(event['location']),getEventDate(event['date']));
    let card = document.createElement('div');
    card.setAttribute('id',event['eventID']);
    card.setAttribute('class','event-card');
    card.append(cardUpper,cardMiddle,cardLower);
    return card;
}

function getEventIcon(category) {
    let image = document.createElement('img')
    image.className = 'event-icon';
    image.setAttribute('src',eventIcons[category]);
    return image;
}

function getParticipationCount(event) {
    let participationDiv = getDiv(['event-participants'])
    participationDiv.append(Object.assign(getDiv(['material-icons']),{innerHTML:'people'}));
    let participationCount = Object.assign(document.createElement('p'),{innerHTML:event['participationCount']});
    participationCount.className = 'participant-count';
    participationDiv.append(participationCount);
    return participationDiv
}

function getTheme(theme) {
    return Object.assign(document.createElement('h2'),{innerHTML:theme});
}

function getEventLocation(location) {
    let locationDiv = getDiv(['event-location']);
    locationDiv.append(Object.assign(document.createElement('img'),{src:locationIcon}));
    locationDiv.append(Object.assign(document.createElement('p'),{innerHTML:location}));
    return locationDiv;
}

function getEventDate(date) {
    let dateDiv = document.createElement('div');
    dateDiv.append(Object.assign(document.createElement('p'),{innerHTML:date}));
    return dateDiv;
}

function getDiv(className) {
    if(Array.isArray(className)) {
        let div = document.createElement('div');
        className.forEach( function (name) {
            div.classList.add(name);
        });
        return div;
    }
}

export {displayEventcards};