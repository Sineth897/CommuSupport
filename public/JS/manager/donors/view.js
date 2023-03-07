let individual = document.getElementById('individual');
let organizations = document.getElementById('organization');

let individualDonorDisplay = document.getElementById('individualDonorDisplay');
let organizationDonorDisplay = document.getElementById('organizationDonorDisplay');

individual.addEventListener('click', function() {
    individual.classList.add('active-heading-page');
    organizations.classList.remove('active-heading-page');
    show(individualDonorDisplay);
    hide(organizationDonorDisplay);
});

organizations.addEventListener('click', function() {
    individual.classList.remove('active-heading-page');
    organizations.classList.add('active-heading-page');
    show(organizationDonorDisplay);
    hide(individualDonorDisplay);
});

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
    element.style.display = "block";
}

