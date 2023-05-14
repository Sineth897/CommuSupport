const canvas = document.getElementById('totalRegChart');
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
const totalRegChart = new Chart(canvas, {
    type: 'line', data: {
        labels: monthsOfYear, datasets: [{
            label: 'Registrations',
                       data: Object.values(monthData),
            borderColor: 'rgb(0,198,21)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(0,198,21)',
            lineTension: 0
        },]
    }, options: {
        title: {
            display: false,
            text: 'Variation of the Registrations over the months',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },

        // responsive: true, maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                },
                gridLines: {
                    display: false
                }
            }], xAxes: [{
                gridLines: {
                    display: true
                },
                ticks: {
                    autoSkip: true,
                    autoSkipPadding: 10
                }
            }]
        },

        legend: {
            labels: {
                usePointStyle: true,
                boxWidth: 4,
                fontSize: 10,
            }
        }
    }
});