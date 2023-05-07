var ctx = document.getElementById('doneeCategoryChart').getContext('2d');
var itemChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(doneeData),
        datasets: [{
            label: 'My Pie Chart',
            data: Object.values(doneeData),
            backgroundColor: [
                '#225E27',
                '#3AAC43',
                '#78D480'
            ]
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Categories of Donees',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },
        responsive: true,
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
