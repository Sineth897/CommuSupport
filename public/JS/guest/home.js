let signupBtn = document.getElementById('signup');
let signupPopup = document.getElementById('signupPopup');
let signupCloseBtn = document.getElementById('signupCloseBtn');

console.log(signupBtn);
console.log(signupPopup);
console.log(signupCloseBtn);
signupBtn.addEventListener('click', function() {
    signupPopup.style.display = 'flex';
});

signupCloseBtn.addEventListener('click', function() {
    signupPopup.style.display = 'none';
});
