import {getData} from "../../request.js";



let usernameSubmitBtn = document.getElementById('usernameSubmit');
let usernameInput = document.getElementById('username');
let usernameError = document.getElementById('usernameError');

usernameSubmitBtn.addEventListener('click',async () => {
    if(usernameInput.value === '') {
        usernameError.innerHTML = 'Please enter your username';
        return;
    }
    if(usernameInput.nextElementSibling !== otpInput) {
        usernameError.innerHTML = '';
    }
    let message = await getData('','post',{
        do: 'checkUsername',
        username: usernameInput.value
    });
    console.log(message);
    if(!message['success']) {
        usernameError.innerHTML = message['message'];
        return;
    }
    usernameInput.setAttribute('disabled','');
    usernameSubmitBtn.style.display = 'none';
    otpDiv.style.display = 'block';
})

let otpDiv = document.getElementById('otpDiv');
let otpSubmitBtn = document.getElementById('otpSubmit');
let otpRequestBtn = document.getElementById('otpRequest');
let otpInput = document.getElementById('otp');
let otpError = document.getElementById('otpError');

otpRequestBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'requestOTP',
        username: usernameInput.value
    });
    otpRequestBtn.setAttribute('disabled','');
    otpRequestBtn.innerHTML = 'Resend OTP';
    otpSubmitBtn.style.display = 'block';
    displayCountDown();
    console.log(message);
});

otpSubmitBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'checkOTP',
        OTP: otpInput.value,
        username: usernameInput.value
    });
    if(message['success']) {
        otpDiv.style.display = 'none';
        newPasswordDiv.style.display = 'block';
    }
    else {
        otpError.innerHTML = message['message'];
    }
})

let otpCountDown = document.getElementById('otpCountDown');

function displayCountDown() {
    let countTill = new Date().getTime() + 60*1000;
    let countDownInterval = setInterval(() => {
        let remainingTime = Math.floor((countTill - new Date().getTime())/1000);
        otpCountDown.innerHTML = `Request new OTP ${remainingTime} seconds`;
        if(remainingTime <= 0) {
            clearInterval(countDownInterval);
            otpCountDown.innerHTML = '';
            otpRequestBtn.removeAttribute('disabled');
        }
    },1000);
}

let newPasswordDiv = document.getElementById('newPasswordDiv');
let newPasswordSubmitBtn = document.getElementById('newPasswordSubmit');
let newPasswordInput = document.getElementById('newPassword');
let newPasswordError = document.getElementById('newPasswordError');
let confirmPasswordInput = document.getElementById('confirmPassword');
let confirmPasswordError = document.getElementById('confirmPasswordError');

newPasswordSubmitBtn.addEventListener('click',async () => {
    if(!validatePasswordFields()) {
        return;
    }
    let message = await getData('','post',{
        do: 'changePassword',
        username: usernameInput.value,
        newPassword: newPasswordInput.value
    });
    console.log(message);
    if(message['success']) {
        newPasswordDiv.style.display = 'none';
        //alert success
        window.location.href = '/login';
    }
    else {
        newPasswordError.innerHTML = message['message'];
    }
});

function validatePasswordFields() {
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if(!passwordRegex.test(newPasswordInput.value)) {
        newPasswordError.innerHTML = 'Password must contain at least 8 characters, one uppercase, one lowercase and one number';
        return false;
    }
    if(newPasswordInput.value !== confirmPasswordInput.value) {
        confirmPasswordError.innerHTML = 'Passwords do not match';
        return false;
    }
    newPasswordError.innerHTML = '';
    confirmPasswordError.innerHTML = '';
    return true;
}