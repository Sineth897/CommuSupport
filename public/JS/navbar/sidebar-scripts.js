let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidenav");
//
// btn.onclick = function(){
//     sidebar.classList.toggle("active");
// }
const logoutLink = document.querySelector("#logout");

sidebar.addEventListener("active", () => {
    const logoutText = document.createElement("span");
    logoutText.innerText = "Logout";
    logoutLink.appendChild(logoutText);
});

