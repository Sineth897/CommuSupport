const currentInventoryDoghnut = document.getElementById('currentInventory').getContext('2d');
const itemChart = new Chart(currentInventoryDoghnut, {
    type: 'doughnut',
    data: {
        labels: Object.keys(currentInventory),
        datasets: [{
            label: 'My Pie Chart',
            data: Object.values(currentInventory),
            backgroundColor: [
                '#225E27',
                '#3AAC43',
                '#78D480'
            ]
        }]
    },
    options: {
        title: {
            display: false,
            text: 'Categories of Donors',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },
        responsive: false,
        maintainAspectRatio: false,
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 10,
                fontSize: 10,
            }
        },
        layout:{
            padding: 0
        }
    }
});
