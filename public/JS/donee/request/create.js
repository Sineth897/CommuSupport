let requestCategory = document.getElementById('requestCategory');
let subcategories = [];

for (let i = 0; i < requestCategory.length; i++) {
    subcategories[requestCategory[i].value] = document.getElementById(requestCategory[i].value);
}

let activeSubcategory = '';
let amountInput = document.getElementById('amountInput');

requestCategory.addEventListener('change', toggleSubcategory);

function toggleSubcategory() {
    if (activeSubcategory !== '') {
        hide(subcategories[activeSubcategory]);
    }
    else {
        hide(amountInput);
    }
    activeSubcategory = requestCategory.value;
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