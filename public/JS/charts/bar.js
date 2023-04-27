const canvas = document.getElementById('myChart');
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
            pointBackgroundColor: 'rgb(0,198,21)'

        }, {
            label: 'Requests within a month',
            data: Object.values(monthData),
            borderColor: 'rgb(0,107,14)',
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            pointBackgroundColor: 'rgb(0,107,14)'
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
                    callback: function (value, index, values) {
                        // Round the value to the nearest quartile and remove the decimal point
                        return Math.round(value * 4) / 4 + '';
                    }
                }, gridLines: {
                    display: false
                }
            }], xAxes: [{
                gridLines: {
                    display: false
                },
                ticks: {
                    autoSkip: true,
                    autoSkipPadding: 40
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
