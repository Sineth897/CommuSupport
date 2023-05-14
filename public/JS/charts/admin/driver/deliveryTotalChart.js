const canvas = document.getElementById('totalDeliveryChart');
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
const colors = ["#013220",
    "#0F8344",
    "#2CBF6C",
    "#42D77C",
    "#66E294",
    "#8CF2B6",
    "#B2FFD9"];
const totalRegChart = new Chart(canvas, {
    type: 'line', data: {
        labels: monthsOfYear, datasets: [{
            label: 'Longer than 10 km',
            data: Object.values(moreThan10),
            borderColor: colors[0],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[0],
            lineTension: 0
        }, {
            label: 'Shorter than 10 km',
            data: Object.values(lessThan10),
            borderColor: colors[1],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[1],
            lineTension: 0
        }]
    }, options: {
        title: {
            display: false,
            text: 'Variation of the Registrations over the months',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },
        //
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