import MapMarker from "../../map/map-marker.js";
import {getData} from "../../request.js";
import mapMarker from "../../map/map-marker.js";

let district = document.getElementById('district');
let activeCity = district.value;
let citySelectDivs = [];
let citySelect = [];

prepareDistrictOptionArray();
prepareCitySelectionArray();

if (activeCity !== '') {
    show(citySelectDivs[activeCity]);
    citySelect[activeCity].removeAttribute("disabled");
}

function prepareDistrictOptionArray() {
    for (let i = 1; i < district.options.length; i++) {
        citySelectDivs[district.options[i].value] = document.getElementById(district.options[i].value);
    }
}

district.addEventListener('change', function () {
    if (activeCity !== '') {
        hide(citySelectDivs[activeCity]);
        citySelect[activeCity].setAttribute("disabled", "");
    }
    activeCity = district.value;
    if (activeCity !== '') {
        show(citySelectDivs[activeCity]);
        citySelect[activeCity].removeAttribute("disabled");
        document.getElementById('city-placeholder').style.display = "none";
    }
});

let individual = document.getElementById('individual');
let organization = document.getElementById('organization');
let doneeType = document.getElementById('doneeType');

//  individual view
individual.addEventListener('click', function () {
    document.getElementById('organizationForm').style.display = "none";
    document.getElementById('individualForm').style.display = "block";
    (document.getElementById('firstname-input').parentNode).style.display = "flex";
    document.getElementById('#organization-name-block').style.display = "none";
    document.getElementById('nicFront').style.display = "block";
    document.getElementById('nicBack').style.display = "block";

    var elements = document.getElementsByClassName('cert-div-org');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'none';
    }

    elements = document.getElementsByClassName('nic-div-org');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'block';
    }

    individual.classList.add('active-heading-page');
    organization.classList.remove('active-heading-page');
    doneeType.value = "Individual";
});

//  Organization View
organization.addEventListener('click', function () {
    document.getElementById('individualForm').style.display = "none";
    document.getElementById('organizationForm').style.display = "block";
    (document.getElementById('firstname-input').parentNode).style.display = "none";
    document.getElementById('#organization-name-block').style.display = "block";
    // document.getElementById('nicFront').style.display = "none";
    // document.getElementById('nicBack').style.display = "none";
    //
    // document.getElementById('certificateFront').style.display = "flex";
    // document.getElementById('certificateBack').style.display = "flex";

    var elements = document.getElementsByClassName('cert-div-org');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'block';
    }

    elements = document.getElementsByClassName('nic-div-org');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.display = 'none';
    }

    individual.classList.remove('active-heading-page');
    organization.classList.add('active-heading-page');
    doneeType.value = "Organization";
});

function prepareCitySelectionArray() {
    for (let key in citySelectDivs) {
        citySelect[key] = citySelectDivs[key].getElementsByTagName('select')[0];
        citySelect[key].setAttribute("disabled", "");
    }
}

function hide(element) {
    element.style.display = "none";
}

function show(element) {
    element.style.display = "flex";
}

document.getElementById('setLocation').addEventListener('click', function () {
    let map = document.getElementById('mapDiv');
    if (map.style.display === 'flex') {
        map.style.display = 'none';
    } else {
        map.style.display = 'flex';
    }
});

document.getElementById('confirmLocation').addEventListener('click', function () {
    document.getElementById('mapDiv').style.display = 'none';
});

const ccCoordinates = await getData('/CommuSupport/communitycenters', 'post', {});

const marker = new MapMarker();


for (let key in citySelect) {
    citySelect[key].addEventListener('change', function () {
        let ccId = citySelect[key].value;
        let cc = ccCoordinates.find(cc => cc['ccID'] === ccId);
        marker.changeLocation(cc['latitude'], cc['longitude']);
    });
}

