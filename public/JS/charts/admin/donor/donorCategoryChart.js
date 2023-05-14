var ctx = document.getElementById('donorCategoryChart').getContext('2d');
var itemChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(donorData),
        datasets: [{
            label: 'My Pie Chart',
            data: Object.values(donorData),
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
        // responsive: true,
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
