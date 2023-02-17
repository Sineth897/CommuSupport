let district = document.getElementById('district');
let activeCity = '';
let citySelectDivs = [];
let citySelect = [];

prepareDistrictOptionArray();
prepareCitySelectionArray();

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

function prepareCitySelectionArray() {
    for( let key in citySelectDivs) {
        citySelect[key] = citySelectDivs[key].getElementsByTagName('select')[0];
        citySelect[key].setAttribute("disabled", "");
    }
}

let category = document.getElementById('category');
let subcategories = [];

for (let i = 0; i < category.length; i++) {
    subcategories[category[i].value] = document.getElementById(category[i].value);
}

let activeSubcategory = '';
let amountInput = document.getElementById('amountInput');

category.addEventListener('change', toggleSubcategory);

function toggleSubcategory() {
    if (activeSubcategory !== '') {
        hide(subcategories[activeSubcategory]);
    }
    else {
        hide(amountInput);
    }
    activeSubcategory = category.value;
    if(activeSubcategory !== '') {
        show(subcategories[activeSubcategory]);
        show(amountInput);
    }
    else {
        hide(amountInput);
    }

}

function hide(element) {
    element.style.display = 'none';
}

function show(element) {
    element.style.display = 'block';
}
