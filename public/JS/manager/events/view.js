import request from "../../request.js";

let filterBtn = document.getElementById('filterBtn');

filterBtn.addEventListener('click', async function() {

    let array = await request().getData('./events/filter', 'POST', {eventCategory:"eventcategory63871b1950"});

    console.log(array);


});

