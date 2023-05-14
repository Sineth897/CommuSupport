import {getData, getTextData} from "./request.js";
import flash from "./flashmessages/flash.js";

// get the change password button
const changePassword =  document.getElementById('change-password');

// use the popUpBackground and popUpContainer from the base layout
const popUpBackground = document.getElementById('popUpBackground');
const popUpContainer = document.getElementById('popUpContainer');


// form format
const form = `
    <div class="close">
        <i class="material-icons">close</i>
    </div>
    
    <form action="#">
    
    <div class="change-password-header">
        <h2> Change Password </h2>
    </div>
    
    <div class="form-group-password">
        <label for="oldPassword">Enter Current Password</label>
        <input type="password" name="password" class="basic-input-field" id="oldPassword" placeholder="Enter Current Password">
        <span class="error" id="oldPasswordError"></span>
    </div>
    
    <div class="form-group-password">
        <label for="oldPassword">Enter New Password</label>
        <input type="password" name="newPassword" class="basic-input-field" id="newPassword" placeholder="New Password">
        <span class="error" id="newPasswordError"></span>
    </div>
    
    <div class="form-group-password">
        <label for="oldPassword">Confirm Password</label>
        <input type="password" name="confirmPassword" class="basic-input-field" id="confirmPassword" placeholder="Confirm Password">
        <span class="error" id="confirmPasswordError"></span>
    </div>
    
    <div class="pass-requirement">
    
        <ul >
           <li id="uppercase">Must contain a Uppercase letter</li>
           <li id="lowercase">Must contain a Lowercase letter</li>
           <li id="number">Must contain a Number</li>
           <li id="specialcharacter">Must contain a Special Character</li>
           <li id="longerthan8">Must be longer than 8 characters</li>
        </ul>
    
    </div>
    
    <div class="confirm-btn">
        <button class="btn btn-primary" id="change-password-btn" type="button">Confirm</button>    
    </div>
    
    </form>`;

// add the form to the popUpContainer
popUpContainer.innerHTML = form;

// get the close button from the popUpContainer
const closeBtn = popUpContainer.querySelector('.close');

// get the password requirements, so that we can add the valid class to them
const uppercase = document.getElementById('uppercase');
const lowercase = document.getElementById('lowercase');
const number = document.getElementById('number');
const specialcharacter = document.getElementById('specialcharacter');
const longerthan8 = document.getElementById('longerthan8');

// add the event listener to close the popup
closeBtn.addEventListener('click', function(e) {

    popUpBackground.style.display = 'none';

});

// get input fields from the form
const oldPassword = document.getElementById('oldPassword');
const newPassword = document.getElementById('newPassword');
const confirmPassword = document.getElementById('confirmPassword');

// add event listeners to the new password input field to validate the password
newPassword.addEventListener('keyup', validate);

function validate() {

    let errorFlag = 0;

    // check if the password is longer than 8 characters
    if(newPassword.value.length > 8) {
        longerthan8.classList.add('valid');
    }
    else {
        longerthan8.classList.remove('valid');
        errorFlag++;
    }

    // check if the password contains an uppercase letter
    if(newPassword.value.match(/[A-Z]/)) {
        uppercase.classList.add('valid');
    }
    else {
        uppercase.classList.remove('valid');
        errorFlag++;
    }

    // check if the password contains a lowercase letter
    if(newPassword.value.match(/[a-z]/)) {
        lowercase.classList.add('valid');
    }
    else {
        lowercase.classList.remove('valid');
        errorFlag++;
    }

    // check if the password contains a number
    if(newPassword.value.match(/[0-9]/)) {
        number.classList.add('valid');
    }
    else {
        number.classList.remove('valid');
        errorFlag++;
    }

    // check if the password contains a special character
    if(newPassword.value.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/)) {
        specialcharacter.classList.add('valid');
    }
    else {
        specialcharacter.classList.remove('valid');
        errorFlag++;
    }

    // check if the password do not meet all the requirements
    if(errorFlag) {
        newPasswordError.innerHTML = 'Your password must meet the requirements';
    }
    else {
        newPasswordError.innerHTML = '';
    }

    return errorFlag;
}

// get the change password button from the form
const changePasswordBtn = document.getElementById('change-password-btn');

// get the spans to show error messages from the form
const oldPasswordError = document.getElementById('oldPasswordError');
const newPasswordError = document.getElementById('newPasswordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');

// add event listener to the change password button
changePasswordBtn.addEventListener('click', async function(e) {

    let errorFlag = 0;

    // check if the old password is empty
    if(oldPassword.value === '') {
        oldPasswordError.innerHTML = 'Please enter your current password';
        errorFlag++;
    }
    else {
        oldPasswordError.innerHTML = '';
    }

    // check if the new password meets the requirements
    if(validate() !== 0) {
        newPasswordError.innerHTML = 'Password does not meet requirements';
        errorFlag++;
    }
    else {
        newPasswordError.innerHTML = '';
    }

    // check if the new password and confirm password match
    if(newPassword.value !== confirmPassword.value) {
        confirmPasswordError.innerHTML = 'Passwords do not match';
        errorFlag++;
    }
    else {
        confirmPasswordError.innerHTML = '';
    }

    // if there is an error, then return false
    if(errorFlag) {
        return false;
    }

    // send the request to change the password
    const result = await getData('/CommuSupport/changepassword', 'post', {
        currentPassword: oldPassword.value,
        newPassword: newPassword.value
    });

    // console.log(result['message'])

    // show the relevant message according to the result
    if(!result['status']) {
        oldPasswordError.innerHTML = 'Current password is incorrect';
        flash.showMessage({type:'error', value: result['message']});
    } else {
        flash.showMessage({type:'success', value: result['message']});
    }

    // reset all input fields
    newPassword.value = '';
    oldPassword.value = '';
    confirmPassword.value = '';

    // hide the popup
    popUpBackground.style.display = 'none';

});

// assign the event listener to show the password form
changePassword.addEventListener('click', function(e) {
    popUpBackground.style.display = 'flex';
});