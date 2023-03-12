// let district = document.getElementById('district');
// let activeCity = document.getElementById('district').value;
// let citySelectDivs = [];
// let citySelect = [];
//
// prepareDistrictOptionArray();
// prepareCitySelectionArray();
//
// function prepareDistrictOptionArray() {
//     for(let i = 1; i < district.options.length; i++) {
//         citySelectDivs[district.options[i].value] = document.getElementById(district.options[i].value);
//     }
// }
//
// district.addEventListener('change', function() {
//     if(activeCity !== '') {
//         hide(citySelectDivs[activeCity]);
//         citySelect[activeCity].setAttribute("disabled", "");
//     }
//     activeCity = district.value;
//     if(activeCity !== '') {
//         show(citySelectDivs[activeCity]);
//         citySelect[activeCity].removeAttribute("disabled");
//     }
// });
//
// function prepareCitySelectionArray() {
//     for( let key in citySelectDivs) {
//         citySelect[key] = citySelectDivs[key].getElementsByTagName('select')[0];
//         citySelect[key].setAttribute("disabled", "");
//     }
//
//     if(activeCity !== '') {
//         citySelect[activeCity].removeAttribute("disabled");
//     }
// }

const donationDiv = document.getElementById('donationDiv');

document.getElementById('createDonation').addEventListener('click', function() {
    show(donationDiv);
});

let category = document.getElementById('category');
let subcategories = [];
let subcategorySelects = [];

for (let i = 1; i < category.length; i++) {
    subcategories[category[i].value] = document.getElementById(category[i].value);
    subcategorySelects[category[i].value] = subcategories[category[i].value].getElementsByTagName('select')[0];
    subcategorySelects[category[i].value].setAttribute("disabled", "");
}

let activeSubcategory = document.getElementById('category').value;
let amountInput = document.getElementById('amountInput');

if(activeSubcategory !== '') {
    show(subcategories[activeSubcategory]);
    subcategorySelects[activeSubcategory].removeAttribute("disabled");
    show(amountInput);
}

category.addEventListener('change', toggleSubcategory);

function toggleSubcategory() {
    if (activeSubcategory !== '') {
        hide(subcategories[activeSubcategory]);
        subcategorySelects[activeSubcategory].setAttribute("disabled", "");
    }
    else {
        hide(amountInput);
    }
    activeSubcategory = category.value;
    if(activeSubcategory !== '') {
        show(subcategories[activeSubcategory]);
        subcategorySelects[activeSubcategory].removeAttribute("disabled");
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
    element.style.display = 'flex';
}
