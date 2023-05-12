const canvas = document.getElementById('totalChart');
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
const totalChart = new Chart(canvas, {
    type: 'line', data: {
        labels: monthsOfYear, datasets: [{
            label: 'Blood Donations',
            data: Object.values(Blood_Donation),
            borderColor: 'rgb(180,54,54)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(180,54,54)',
            lineTension: 0

        }, {
            label: 'Eye Clinics',
            data: Object.values(Eye_clinic),
            borderColor: 'rgb(41,171,55)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(41,171,55)',
            lineTension: 0
        }, {
            label: 'Donation Collections',
            data: Object.values(Donation_collection),
            borderColor: 'rgb(86,86,86)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(86,86,86)',
            lineTension: 0
        }, {
            label: 'Fundraising',
            data: Object.values(Fundraising),
            borderColor: 'rgb(0,107,14)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(0,107,14)',
            lineTension: 0
        }, {
            label: 'Give Aways',
            data: Object.values(Give_away),
            borderColor: 'rgb(0,107,14)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(0,107,14)',
            lineTension: 0
        }, {
            label: 'Heart Clinics',
            data: Object.values(Heart_clinic),
            borderColor: 'rgb(0,107,14)',
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: 'rgb(0,107,14)',
            lineTension: 0
        }]
    }, options: {
        title: {
            display: false,
            text: 'Variation of the Requests over the months',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        }, scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true, stepSize: 1
                }, gridLines: {
                    display: false
                }
            }], xAxes: [{
                gridLines: {
                    display: true
                }, ticks: {
                    autoSkip: true, autoSkipPadding: 10
                }
            }]
        },
        // responsive: true, maintainAspectRatio: false,
        legend: {
            labels: {
                usePointStyle: true, boxWidth: 4, fontSize: 10,
            }
        }
    }
});