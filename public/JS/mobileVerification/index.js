import {getData} from "../request.js";


let requestBtn = document.getElementById('requestBtn');
let otpInput = document.getElementById('otpInput');
let otpInputDiv = document.getElementById('otpInputDiv');
let submitBtn = document.getElementById('submitBtn');
let otpCountDown = document.getElementById('otpCountDown');
let otpError = document.getElementById('otpError');

otpInputDiv.style.display = 'none';

requestBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'requestOTP',
    });
    requestBtn.setAttribute('disabled','');
    requestBtn.innerHTML = 'Resend OTP';
    otpInputDiv.style.display = 'block';
    displayCountDown();
});

submitBtn.addEventListener('click',async () => {
    let message = await getData('','post',{
        do: 'checkOTP',
        OTP: otpInput.value,
    });
    if(message['success']) {
        otpInputDiv.style.display = 'none';
        // window.location.replace('/CommuSupport/');
        showSuccess();
    }
    else {
        otpError.innerHTML = message['message'];
    }
})

function showSuccess() {

    const gridBackground = document.querySelector('.grid-background');

    gridBackground.innerHTML = `
    <div class="otp-success">
        <p>Your mobile number has been Verified. Please continue to Home page</p>
        <a href="/CommuSupport/" class="btn btn-primary">Home</a>
    </div>
    `;
}

// showSuccess();


function displayCountDown() {
    let countTill = new Date().getTime() + 60*1000;
    let countDownInterval = setInterval(() => {
        let remainingTime = Math.floor((countTill - new Date().getTime())/1000);
        otpCountDown.innerHTML = `Request new OTP ${remainingTime} seconds`;
        if(remainingTime <= 0) {
            clearInterval(countDownInterval);
            otpCountDown.innerHTML = '';
            requestBtn.removeAttribute('disabled');
        }
    },1000);
}