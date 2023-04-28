const canvas = document.getElementById('totalChart');
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
const myChart = new Chart(canvas, {
    type: 'line', data: {
        labels: monthsOfYear, datasets: [{
            label: 'Requests within 7 days',
            data: Object.values(weekData),
            borderColor: 'rgb(0,198,21)',
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            pointBackgroundColor: 'rgb(0,198,21)',
            lineTension: 0

        }, {
            label: 'Requests within a month',
            data: Object.values(monthData),
            borderColor: 'rgb(0,107,14)',
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            pointBackgroundColor: 'rgb(0,107,14)',
            lineTension: 0
        },]
    }, options: {
        title: {
            display: true,
            text: 'Requests',
            fontSize: 20
        },

        responsive: false, maintainAspectRatio: false, scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                }, gridLines: {
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
