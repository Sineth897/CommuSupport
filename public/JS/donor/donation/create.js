import flash from "../../flashmessages/flash.js";
import {getData} from "../../request.js";

const donationDiv = document.getElementById('donationDiv');

document.getElementById('createDonation').addEventListener('click', function() {
    show(donationDiv);
});

document.getElementById('cancelDonation').addEventListener('click', function() {
    hide(donationDiv);
});


//Functions and variables for the subcategory select
let category = document.getElementById('category');
let subcategories = [];
let subcategorySelects = [];

//for each subcategory select, add it to the subcategories array and disable it
for (let i = 1; i < category.length; i++) {
    subcategories[category[i].value] = document.getElementById(category[i].value);
    subcategorySelects[category[i].value] = subcategories[category[i].value].getElementsByTagName('select')[0];
    subcategorySelects[category[i].value].setAttribute("disabled", "");
}

let activeSubcategory = document.getElementById('category').value;
let amountInput = document.getElementById('amountInput');
let amount = document.getElementById('amount');

// if(activeSubcategory !== '') {
//     show(subcategories[activeSubcategory]);
//     subcategorySelects[activeSubcategory].removeAttribute("disabled");
//     show(amountInput);
// }

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

document.getElementById('confirmDonation').addEventListener('click', async function() {
    if(!validateDonation()) {
        return;
    }

    let donation = {
        amount: document.getElementById('amount').value,
        category: category.value,
        item: subcategorySelects[activeSubcategory].value
    };

    const result = await getData('./donation/create', 'POST', donation);

    if(result['status']) {
        flash.showMessage({type: 'success', value: result['msg']},5000);
        hide(donationDiv);
    }
    else {
        console.log(result['msg']);
        flash.showMessage({type: 'error', value: result['msg']},5000);
    }
});

function validateDonation() {
    if(activeSubcategory === '') {
        flash.showMessage({type: 'error', value: 'Please select a category!'},5000);
        return false;
    }
    if(subcategorySelects[activeSubcategory].value === '') {
        flash.showMessage({type: 'error', value: 'Please select a item to donate!'},5000);
        return false;
    }
    if(parseInt(amount.value) < 1 || amount.value === '') {
        flash.showMessage({type: 'error', value: 'Please enter valid amount!'},5000);
        return false;
    }
    return true;
}


