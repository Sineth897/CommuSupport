const distanceDoghnut = document.getElementById('chartByEventType').getContext('2d');
const itemChart = new Chart(distanceDoghnut, {
    type: 'doughnut',
    data: {
        labels: Object.keys(eventsByType),
        datasets: [{
            label: 'My Pie Chart',
            data: Object.values(eventsByType),
            backgroundColor: [
                '#225E27',
                '#3AAC43',
                '#78D480',
                '#A9E6A0',
                '#D0F0C0',
                '#F0F7E2'
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
        // responsive: false,
        // maintainAspectRatio: false,
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
