const chartDiv = document.querySelector('#eventPosted');

let events = [];

dates.forEach(date => {
    events.push(eventData[date] ? eventData[date] : 0);
});


const variationChart = new Chart(chartDiv, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'No. events per day',
            data: events,
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
            text: 'Events hold per day over the months',
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