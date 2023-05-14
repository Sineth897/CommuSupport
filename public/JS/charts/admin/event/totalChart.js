const canvas = document.getElementById('totalChart');
const monthsOfYear = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
const colors = ["#013220",
    "#0F8344",
    "#2CBF6C",
    "#42D77C",
    "#66E294",
    "#8CF2B6",
    "#B2FFD9"];
const totalChart = new Chart(canvas, {
    type: 'line', data: {
        labels: monthsOfYear, datasets: [{
            label: 'Blood Donations',
            data: Object.values(Blood_Donation),
            borderColor: colors[0],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[0],
            lineTension: 0

        }, {
            label: 'Eye Clinics',
            data: Object.values(Eye_clinic),
            borderColor: colors[1],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[1],
            lineTension: 0
        }, {
            label: 'Donation Collections',
            data: Object.values(Donation_collection),
            borderColor: colors[2],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[2],
            lineTension: 0
        }, {
            label: 'Fundraising',
            data: Object.values(Fundraising),
            borderColor: colors[3],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[3],
            lineTension: 0
        }, {
            label: 'Give Aways',
            data: Object.values(Give_away),
            borderColor: colors[4],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[4],
            lineTension: 0
        }, {
            label: 'Heart Clinics',
            data: Object.values(Heart_clinic),
            borderColor: colors[5],
            borderWidth: 2,
            fill: false,
            pointRadius: 3,
            pointBackgroundColor: colors[5],
            lineTension: 0
        }]
    }, options: {
        title: {
            display: false,
            // text: 'Variation of the Requests over the months',
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