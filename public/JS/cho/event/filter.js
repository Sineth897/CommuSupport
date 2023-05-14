
let filterOptions = document.getElementById('filterOptions');
document.getElementById('filter').addEventListener('click', function(e) {
    if(filterOptions.style.display === 'block') {
        filterOptions.style.display = 'none';
    } else {
        filterOptions.style.display = 'block';
    }
    sortOptions.style.display = 'none';
});

let filterBtn = document.getElementById('filterBtn');
let eventsDiv = document.getElementById('eventDisplay')

let sortByDate = document.getElementById('filter');

filterBtn.addEventListener('click', async function() {

    let filterValues = {};
    let sort = {};

    if (eventCategory.value) {
        filterValues['eventCategoryID'] = eventCategory.value;
    }

    if (sortByDate.checked) {
        sort['DESC'] = ['date'];
    }

    let array = await getData('./event/filter', 'POST', {filters:filterValues, sortBy:sort});



});