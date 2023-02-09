
let eventIcons = [];
let locationIcon = "/CommuSupport/public/src/icons/event/location.svg";
let participationIcon = "/CommuSupport/public/src/icons/event/participants.svg";

function displayEventcards(eventsDiv,array) {
    eventIcons = array['icons'];
    eventsDiv.innerHTML = '';
    if(array['events'].length === 0) {
        let heading = document.createElement('h2')
        heading.innerHTML = "There no events to display"
        eventsDiv.appendChild(heading);
    }
    else {
        array['events'].forEach( function (event) {
            eventsDiv.append(eventCard(event));
        });
    }
}

function eventCard(event) {
    let cardUpper = document.createElement('div');
    let cardMiddle = document.createElement('div');
    let cardLower = document.createElement('div');
    cardUpper.append(getEventIcon(event['eventCategoryID']),getParticipationCount(event));
    cardMiddle.append(getTheme(event['theme']));
    cardLower.append(getEventLocation(event['location']),getEventDate(event['date']));
    let card = document.createElement('div');
    card.setAttribute('id',event['eventID']);
    card.setAttribute('class','eventCard');
    card.append(cardUpper,cardMiddle,cardLower);
    return card;
}

function getEventIcon(category) {
    let imageDiv = document.createElement('Div');
    let image = document.createElement('img')
    image.setAttribute('src',eventIcons[category]);
    imageDiv.appendChild(image);
    return imageDiv;
}

function getParticipationCount(event) {
    let participationDiv = document.createElement('div');
    participationDiv.append(Object.assign(document.createElement('img'),{src:participationIcon}));
    participationDiv.append(Object.assign(document.createElement('p'),{innerHTML:event['participationCount']}));
    return participationDiv
}

function getTheme(theme) {
    return Object.assign(document.createElement('h2'),{innerHTML:theme});
}

function getEventLocation(location) {
    let locationDiv = document.createElement('div');
    locationDiv.append(Object.assign(document.createElement('img'),{src:locationIcon}));
    locationDiv.append(Object.assign(document.createElement('p'),{innerHTML:location}));
    return locationDiv;
}

function getEventDate(date) {
    let dateDiv = document.createElement('div');
    dateDiv.append(Object.assign(document.createElement('p'),{innerHTML:date}));
    return dateDiv;
}

export {displayEventcards};