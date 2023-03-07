let district = document.getElementById('district');
let activeCity = district.value;
let citySelectDivs = [];
let citySelect = [];

prepareDistrictOptionArray();
prepareCitySelectionArray();

if(activeCity !== '') {
    show(citySelectDivs[activeCity]);
    citySelect[activeCity].removeAttribute("disabled");
}

function prepareDistrictOptionArray() {
    for(let i = 1; i < district.options.length; i++) {
        citySelectDivs[district.options[i].value] = document.getElementById(district.options[i].value);
    }
}

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
let doneeType = document.getElementById('doneeType');

individual.addEventListener('click', function() {
    document.getElementById('organizationForm').style.display = "none";
    document.getElementById('individualForm').style.display = "block";
    individual.classList.add('active-heading-page');
    organization.classList.remove('active-heading-page');
    doneeType.value = "Individual";
});

organization.addEventListener('click', function() {
    document.getElementById('individualForm').style.display = "none";
    document.getElementById('organizationForm').style.display = "block";
    individual.classList.remove('active-heading-page');
    organization.classList.add('active-heading-page');
    doneeType.value = "Organization";
});

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