let signupBtn = document.getElementById('signup');
let signupPopup = document.getElementById('signupPopup');
let signupCloseBtn = document.getElementById('signupCloseBtn');

signupBtn.addEventListener('click', function() {
    signupPopup.style.display = 'flex';
});

signupCloseBtn.addEventListener('click', function() {
    signupPopup.style.display = 'none';
});

document.getElementById('readmore').addEventListener('click', function() {
    signupBtn.click();
});

