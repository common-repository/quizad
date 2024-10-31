(function (win, $, RequestsFactory, Chart) {

    var ctx_live = document.getElementById("myChart");
    var myChart = new Chart(ctx_live, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: '# of Views',
                data: [],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',

                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',

                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {display: false},
            title: {
                display: false,
                text: 'Title of chart'
            }, scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Liczba wyświetleń"
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Data"
                    }
                }]
            }
        }
    });

    var updateChart = function (jsonString) {
        var data = JSON.parse(jsonString);
        var items = data.statisticsList;

        items.forEach(function (item) {
            myChart.data.labels.push(item.date);
            myChart.data.datasets[0].data.push(item.views);
        });
        myChart.update();
    };
    $(win).load(function (e) {

        e.preventDefault();

        var request = RequestsFactory();
        request.onSuccess(function (status, text) {
            updateChart(text);
        });
        request.get(ajaxurl + '?action=quizAd_statistics_data', {});
    });


})(window, jQuery, QuizAdRequestFactory, Chart);

