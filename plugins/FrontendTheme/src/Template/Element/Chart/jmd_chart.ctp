<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script>
    window.onload = function () {
        var dataPoints1 = [];
        var dataPoints2 = [];
        function chart(data, bool = false) {
            // Create a timer
            var start = $.now();
            var yesterday = start - 86400;
            // Create the chart
            Highcharts.stockChart('chartContainer', {
                rangeSelector: {
                    buttons: [{
                            type: 'day',
                            count: 3,
                            text: '3d'
                        }, {
                            type: 'week',
                            count: 1,
                            text: '1w'
                        }, {
                            type: 'month',
                            count: 1,
                            text: '1m'
                        }, {
                            type: 'month',
                            count: 6,
                            text: '6m'
                        }, {
                            type: 'year',
                            count: 1,
                            text: '1y'
                        }, {
                            type: 'all',
                            text: 'All'
                        }],
                    selected: 3
                },
                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: '<?= $name ?>'
                },
                subtitle: {
                    text: document.ontouchstart === undefined ?
                            'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    title: {
                        text: '<?= $name ?>'
                    }
                },
                plotOptions: {
                    area: {
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[0]],
                                [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                            ]
                        },
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },

                series: [{
                        animation:bool,
                        type: 'area',
                        name: '<?= $symbol; ?>',
                        data: data,
                    }]

            });
        };

        var updateInterval = 15000;

        function in_array(array_sss, text)
        {
            var bool = false;
            $.each(array_sss, function (index, value) {
                if (value == text) {
                    bool = 'true';
                }
            });
            return bool;
        }

        function updateChart(count,bool) {
            count = count || 1;
            var deltaY1, deltaY2;
            var getCommentCountUrl = $('#chartContainer').attr("data-chart-url");
            $.ajax({
                type: "get",
                url: getCommentCountUrl,
                success: function (response) {
                    $.each(response.stockInfo.info, function (index, value) {
                        if ((dataPoints1.length > 0 && in_array(dataPoints1, value['id']) == false) || count == 1) {

                            dataPoints1.push(
                                value['id'],
                            );

                            dataPoints2.push([
                                new Date(value['last_refreshed']),
                                value['1. open']
                            ]);
                        }
                    });

                    chart(dataPoints2,bool);
                },
                error: function (e) {

                }
            });
        }
        // generates first set of dataPoints 
        updateChart(1,true);
        setInterval(function () {
            updateChart(2,false)
        }, updateInterval);

    }
</script>
<div data-symbol="<?= $symbol; ?>"
     data-chart-url="<?= $this->Url->build(['_name' => 'get_chart_data', 'symbol' => $symbol]); ?>"
     id="chartContainer" style="height: 500px; width: 100%;">
</div>
