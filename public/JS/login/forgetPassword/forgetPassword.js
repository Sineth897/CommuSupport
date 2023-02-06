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

otpRequestBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'requestOTP',
        username: usernameInput.value
    });
    // otpRequestBtn.setAttribute('disabled','');
    otpRequestBtn.innerHTML = 'Resend OTP';
    otpSubmitBtn.style.display = 'block';
    console.log(message);
});

otpSubmitBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'checkOTP',
        otp: "otpInput.value"
    });
    console.log(message);
})