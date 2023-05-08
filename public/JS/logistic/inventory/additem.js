import { getData } from "../../request.js";
import {displayTable} from "../../components/table.js";
import flash from "../../flashmessages/flash.js";

let addBtn = document.getElementById('addBtn');
let filterBtn = document.getElementById('filterBtn');
let closeBtn = document.getElementById('closeBtnDiv');
let itemForm = document.getElementById('itemForm');
let inventoryDisplay = document.getElementById('inventoryDisplay');

let category = document.getElementById('category');
let activeSubcategory = '';
let subcategorySelectDivs = [];
let subcategorySelect = [];

const confirmBtn = document.getElementById('addToInventory');
const amount = document.getElementById('amount');
const remark = document.getElementById('remark');

let filterCategory = document.getElementById('filterCategory');
let sortLastUpdated = document.getElementById('sortLastUpdated');
let sortAmount = document.getElementById('sortAmount');

const popupBackground = document.getElementById('popUpBackground');
popupBackground.append(itemForm);
show(itemForm);

prepareCategoryOptionArray();
prepareSubcategorySelectionArray();
addBtn.addEventListener('click', function() {
    show(popupBackground);
});

closeBtn.addEventListener('click', function() {
    hide(popupBackground);
});

category.addEventListener('change', function() {
    if(activeSubcategory !== '') {
        hide(subcategorySelectDivs[activeSubcategory]);
    }
    activeSubcategory = category.value;
    if(activeSubcategory !== '') {
        show(subcategorySelectDivs[activeSubcategory]);
    }
});

confirmBtn.addEventListener('click', async function() {
    if(verifyForm()) {
        let data = {
            subcategoryID: subcategorySelect[activeSubcategory].value,
            amount: amount.value,
            remark: remark.value };
        let array = await getData('./inventory/add', 'POST', { data:data });
        if(array['success']) {
            flash.showMessage({type: 'success', value: 'Item added to inventory'},3000);
            filterBtn.click();
            resetAddForm();
            hide(popupBackground);
        }
        else {
            flash.showMessage({type: 'error', value: 'Item could not be added to inventory'},3000);
        }
    }
});

let filterOptions = document.getElementById('filterOptions');
let sortOptions = document.getElementById('sortOptions');

document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
    sortOptions.style.display = 'none';
});

document.getElementById('sort').addEventListener('click', function(e) {
    if(sortOptions.style.display === 'block') {
        sortOptions.style.display = 'none';
    } else {
        sortOptions.style.display = 'block';
    }
    filterOptions.style.display = 'none';
});

filterBtn.addEventListener('click', async function() {
    let filters = {};
    let sort = {DESC:[]};
    if(filterCategory.value !== '') {
        filters['categoryID'] = filterCategory.value;
    }
    if(sortLastUpdated.checked) {
        sort['DESC'].push('updatedTime');
    }
    if(sortAmount.checked) {
        sort['DESC'].push('amount');
    }
    let array = await getData('./inventory/filter', 'POST', { filters: filters, sortBy: sort });

    let data = {
        headings: ['Item Name', 'Amount', 'Unit', 'Last Updated'],
        keys: ['subcategoryName', 'amount', 'scale', 'updatedTime'],
        data: array
    };
    filterOptions.style.display = 'none';
    displayTable(inventoryDisplay, data);
});

document.getElementById('sortBtn').addEventListener('click', function() {
    filterBtn.click();
    sortOptions.style.display = 'none';
});


function verifyForm() {
    return required(category) && required(subcategorySelect[activeSubcategory]) && required(amount) && positive(amount) && required(remark);
}

function required(element) {
    if(element.value === '') {
        element.nextElementSibling.innerHTML = "This field is required";
        return false;
    }
    element.nextElementSibling.innerHTML = "";
    return true;
}

function resetAddForm() {
    category.value = '';
    activeSubcategory = '';
    amount.value = '';
    remark.value = '';
    for(let i = 0; i < subcategorySelect.length; i++) {
        subcategorySelect[i].value = '';
    }
}

function positive(element) {
    if(parseFloat(element.value) <= 0) {
        element.nextElementSibling.innerHTML = "This field must be a valid amount";
        return false;
    }
    element.nextElementSibling.innerHTML = "";
    return true;
}




function prepareCategoryOptionArray() {
    for(let i = 1; i < category.options.length; i++) {
        subcategorySelectDivs[category.options[i].value] = document.getElementById(category.options[i].value);
    }
}

function prepareSubcategorySelectionArray() {
    for( let key in subcategorySelectDivs) {
        subcategorySelect[key] = subcategorySelectDivs[key].getElementsByTagName('select')[0];
    }
}

function toggleHidden(element) {
    if(element.style.display === "none") {
        show(element);
    } else {
        hide(element);
    }
}

function hide(element) {
    element.style.display = "none";
}

function show(element) {
    element.style.display = "flex";
}