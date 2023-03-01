let requestCategory = document.getElementById('requestCategory');
let subcategories = [];

for (let i = 1; i < requestCategory.length; i++) {
    subcategories[requestCategory[i].value] = document.getElementById(requestCategory[i].value);
    (subcategories[requestCategory[i].value].getElementsByTagName('select')[0]).setAttribute('disabled', '');
}

let activeSubcategory = requestCategory.value;
let amountInput = document.getElementById('amountInput');

if (activeSubcategory !== '') {
    show(subcategories[activeSubcategory]);
    (subcategories[activeSubcategory].getElementsByTagName('select')[0]).removeAttribute('disabled');
    show(amountInput);
}

requestCategory.addEventListener('change', toggleSubcategory);

function toggleSubcategory() {
    if (activeSubcategory !== '') {
        hide(subcategories[activeSubcategory]);
        (subcategories[activeSubcategory].getElementsByTagName('select')[0]).setAttribute('disabled', '');
    }
    else {
        hide(amountInput);
    }
    activeSubcategory = requestCategory.value;
    if(activeSubcategory !== '') {
        show(subcategories[activeSubcategory]);
        (subcategories[activeSubcategory].getElementsByTagName('select')[0]).removeAttribute('disabled');
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