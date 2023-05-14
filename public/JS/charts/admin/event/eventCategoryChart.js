var ctx = document.getElementById('itemChart').getContext('2d');
var itemChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(itemData),
        datasets: [{
            label: 'My Pie Chart',
            data: Object.values(itemData),
            backgroundColor: [
                // '#1c8625',
                // '#3AAC43',
                // '#78D480',
                // '#B2E8A1',
                // '#D9F2C7',
                // '#F2F2F2',
                // '#006400', '#556B2F', '#008080', '#7FFF00', '#00FF00', '#9ACD32'
                '#013220',
                '#0F8344',
                '#2CBF6C',
                '#42D77C',
                '#66E294',
                '#8CF2B6',
                '#B2FFD9'
            ]
        }]
    },
    options: {
        title: {
            display: false,
            text: 'Categories of Requests',
            fontSize: 24,
            fontColor: '#000',
            fontFamily: 'inter'
        },
        // responsive: true,
        // maintainAspectRatio: true,
        legend: {
            display: true,
            position: 'top',
            labels: {
                boxWidth: 10,
                fontSize: 10,
            }
        },
        layout: {
            padding: 0
        }
    }
});
