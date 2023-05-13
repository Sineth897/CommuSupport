const chartDiv = document.querySelector('#inventoryVariation');

let logs = [];

dates.forEach(date => {
    logs.push(inventoryData[date] ? inventoryData[date] : 0);
});

const variationChart = new Chart(chartDiv, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'No. of log entries per day',
            data: logs,
            borderColor: 'rgb(0,198,21)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(0,198,21)',
            lineTension: 0,
        }]
    },
    options: {
        title: {
            display: false,
            text: 'Variation of the transactions per day over the months',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },
        scales: {
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
                    autoSkipPadding: 10,
                    stepSize: 2
                }
            }]
        },
        // responsive: true,
        // maintainAspectRatio: false,
        legend: {
            labels: {
                usePointStyle: true,
                boxWidth: 4,
                fontSize: 10,
            }
        }
    }
})