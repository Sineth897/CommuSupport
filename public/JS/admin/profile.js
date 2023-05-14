import {getData, getTextData} from "../request.js";
import flash from "../flashmessages/flash.js";

const popUpBackground = document.querySelector('#popUpBackground');
const popUpContainer = document.querySelector('#popUpContainer');

const closePopUpFunction = () => {
    popUpBackground.style.display = 'none';
    popUpContainer.innerHTML = '';
}

function addClosePopup () {

    const closeBtn = popUpContainer.querySelector('.close');
    closeBtn.addEventListener('click', closePopUpFunction);

}

//function for adding new category

const addCategoryBtn = document.getElementById('newCategoryBtn');

const addCategoryForm = `
    <div class="close">
        <i class="material-icons">close</i>
    </div>
    
    <form action="#">
    
    <div class="change-password-header">
        <h2> Add a new category of Items </h2>
    </div>
    
    <div class="form-group-password">
        <label for="newCategory">Enter Current Password</label>
        <input type="text" name="newCategory" class="basic-input-field" id="newCategory" placeholder="Enter New Category">
        <span class="error" id="newCategoryError"></span>
    </div> 
    
    <div class="confirm-btn">
        <button class="btn btn-primary" type="button">Confirm</button>    
    </div>   
    
    </form>`;

addCategoryBtn.addEventListener('click', async () => {

    popUpBackground.style.display = 'flex';
    popUpContainer.innerHTML = addCategoryForm;

    addClosePopup();

    popUpContainer.querySelector('button').addEventListener('click', addCategory);

})

async function addCategory() {

    const newCategory = document.getElementById('newCategory');

    const category = newCategory.value;

    if (category === '') {
        document.getElementById('newCategoryError').innerHTML = 'Category cannot be empty';
        return;
    }else {
        document.getElementById('newCategoryError').innerHTML = '';
    }

    const data = await getData('./profile/addcategory', 'post',{categoryName:category});

    // console.log(data);

    if (!data['success']) {
        flash.showMessage({type: 'error', value: data['message']});
        closePopUpFunction();
    } else {
        flash.showMessage({type: 'success', value: data['message']});
        closePopUpFunction();
    }

}

// function for adding new subcategories

const addSubcategoryBtn = document.getElementById('newSubcategoryBtn');

const addSubcategoryForm = `
    <div class="close">
        <i class="material-icons">close</i>
    </div>
    
    <form action="#">
    
    <div class="change-password-header">
        <h2> Add a new subcategory of Items </h2>
    </div>
    
    <div class="form-group-password">
    
        <label for="category">Select Category</label>
        <select name="category" id="category" class="basic-input-field">
            <option value="0">Select Category</option>
            categoryPlaceholder
        </select>
        <span class="error" id="categoryError"></span>
    </div>
    
    <div class="form-group-password">
        <label for="newSubcategory">Enter New Subcategory</label>
        <input type="text" name="newSubcategory" class="basic-input-field" id="newSubcategory" placeholder="Enter New Subcategory">
        <span class="error" id="newSubcategoryError"></span>
    </div>
    
    <div class="form-group-password">
        <label for="scale">Enter Scale For new Subcategory</label>
        <input type="text" name="scale" class="basic-input-field" id="scale" placeholder="Enter Scale">
        <span class="error" id="scaleError"></span>
    </div>
    
    <div class="confirm-btn">
        <button class="btn btn-primary" type="button">Confirm</button>
    </div>
    
    </form>`;

addSubcategoryBtn.addEventListener('click', async () => {

    const categories = await getData('./profile/getcategories', 'post',{});

    // console.log(categories);

    const categoryPlaceholder = prepareCategories(categories['data']);

    popUpBackground.style.display = 'flex';
    popUpContainer.innerHTML = addSubcategoryForm.replace('categoryPlaceholder', categoryPlaceholder);
    addClosePopup();

    popUpContainer.querySelector('button').addEventListener('click', addSubcategory);

});

async function addSubcategory(e) {

    const category = document.getElementById('category');
    const newSubcategory = document.getElementById('newSubcategory');
    const scale = document.getElementById('scale');

    const categoryValue = category.value;
    const newSubcategoryValue = newSubcategory.value;
    const scaleValue = scale.value;

    if (categoryValue === '0') {
        document.getElementById('categoryError').innerHTML = 'Please select a category';
        return;
    }else {
        document.getElementById('categoryError').innerHTML = '';
    }

    if (newSubcategoryValue === '') {
        document.getElementById('newSubcategoryError').innerHTML = 'Please enter a subcategory';
        return;
    }else {
        document.getElementById('newSubcategoryError').innerHTML = '';
    }

    if (scaleValue === '') {
        document.getElementById('scaleError').innerHTML = 'Please enter a scale';
        return;
    }else {
        document.getElementById('scaleError').innerHTML = '';
    }

    const data = await getData('./profile/addsubcategory', 'post', {categoryID: categoryValue, subcategoryName: newSubcategoryValue, scale: scaleValue});

    console.log(data);

    if (!data['success']) {
        flash.showMessage({type: 'error', value: data['message']});
        closePopUpFunction();
    } else {
        flash.showMessage({type: 'success', value: data['message']});
        closePopUpFunction();
    }

}

function prepareCategories(categories) {

    let categoryOptions = ``;

    const categoryKeys = Object.keys(categories);

    categoryKeys.forEach((category) => {
        categoryOptions += `<option value="${category}">${categories[category]}</option>`;
    })

    return categoryOptions;
}




