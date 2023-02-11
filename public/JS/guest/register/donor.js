let district = document.getElementById('district');
let activeCity = '';
let citySelectDivs = [];
let citySelect = [];

prepareDistrictOptionArray();
prepareCitySelectionArray();

district.addEventListener('change', function() {
    if(activeCity !== '') {
        hide(citySelectDivs[activeCity]);
        citySelect[activeCity].setAttribute("disabled", "");
    }
    activeCity = district.value;
    if(activeCity !== '') {
        show(citySelectDivs[activeCity]);
        citySelect[activeCity].removeAttribute("disabled");
    }
});

let individual = document.getElementById('individual');
let organization = document.getElementById('organization');
let donorType = document.getElementById('donorType');

individual.addEventListener('click', function() {
    document.getElementById('organizationForm').style.display = "none";
    document.getElementById('individualForm').style.display = "block";
    donorType.value = "Individual";
});

organization.addEventListener('click', function() {
   document.getElementById('individualForm').style.display = "none";
    document.getElementById('organizationForm').style.display = "block";
    donorType.value = "Organization";
});

function prepareDistrictOptionArray() {
    for(let i = 1; i < district.options.length; i++) {
        citySelectDivs[district.options[i].value] = document.getElementById(district.options[i].value);
    }
}

function prepareCitySelectionArray() {
    for( let key in citySelectDivs) {
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