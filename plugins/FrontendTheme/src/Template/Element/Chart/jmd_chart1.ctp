<script>
    window.onload = function () {
        var dataPoints1 = [];
        var dataPoints2 = [];
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            zoomEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            exportEnabled: true,
            title: {
                text: "<?= $name; ?>- " + (new Date()).getFullYear(),
                fontSize: 17
            },
            axisX: {
                titleFontColor: "grey",
                fontSize: 13,
                valueFormatString: "MMM DD",
                lineColor: "white",
                fontColor: "grey",
                 tickColor: "grey",
                 labelFontColor: "grey"
//                minimum: new Date().setHours(0)
            },
            axisY: {
                titleFontColor: "grey",
                fontSize: 13,
                gridThickness: 0.2,
                prefix: "$",
                title: "Price",
                lineColor: "white",
                gridColor: "grey",
                tickColor: "grey",
                labelFontColor: "grey"
            },
            toolTip: {
                shared: true
            },
            legend: {
                reversed: true,
                fontSize: 17,
                cursor: "pointer",
                fontColor: "grey",
                itemclick: toggleDataSeries
            },
            data: [{
                    type: "candlestick",
                    yValueFormatString: "$####.00",
                    xValueFormatString: "DD MMM hh:mm:ss TT",
                    showInLegend: true,
                    name: "<?= $symbol ?>",
                    dataPoints: dataPoints1,
                    cursor: "pointer",
                },
                {
                    type: "line",
                    name: "",
                    yValueFormatString: "$####.00",
                    xValueFormatString: "DD MMM hh:mm:ss TT",
                    dataPoints: dataPoints2,
                    cursor: "pointer",
                }]
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

        var updateInterval = 5000;
        // initial value
        var yValue1 = 0;
        var yValue2 = 0;

        function in_array(array_sss, text)
        {
            var bool = false;
            $.each(array_sss, function (index, value) {
                if (value.id == text) {
                    bool = 'true';
                }
            });
            return bool;
        }

        function updateChart(count) {
            count = count || 1;
            var deltaY1, deltaY2;
            var getCommentCountUrl = $('#chartContainer').attr("data-chart-url");
            $.ajax({
                type: "get",
                url: getCommentCountUrl,
                success: function (response) {
                    $.each(response.stockInfo.info, function (index, value) {
                        if ((dataPoints1.length > 0 && in_array(dataPoints1, value['id']) == false) || count == 1) {
                            yValue2 = value['1. open'];
                            // pushing the new values
                            dataPoints1.push({
                                id: value['id'],
                                x: new Date(value['last_refreshed']),
                                y: [value['1. open'], value['2. high'], value['3. low'], value['4. close']]
                            });
                            dataPoints2.push({
                                x: new Date(value['last_refreshed']),
                                y: yValue2
                            });
                        }
                    });
                },
                error: function (e) {

                }
            });
            // updating legend text with  updated with y Value 
            chart.options.data[0].legendText = " <?= $symbol ?>  $" + yValue2;
            chart.render();
        }
        // generates first set of dataPoints 
        updateChart(1);
        setInterval(function () {
            updateChart(2)
        }, updateInterval);

    }
</script>
<div data-symbol="<?= $symbol; ?>"
     data-chart-url="<?= $this->Url->build(['_name' => 'get_chart_data', 'symbol' => $symbol]); ?>"
     id="chartContainer" style="height: 570px; width: 100%;"></div>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!--<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];</script></head>
<body>
    <div data-symbol="<?= $symbol; ?>"
         data-chart-url="<?= $this->Url->build(['_name' => 'get_chart_data', 'symbol' => $symbol]); ?>"
         id='myChart'></div>
    <script>
        window.onload = function () {
            var dataPoints1 = [];
            var myConfig = {
                "type": "stock",
                "title": {
                    "text": "S&P 500",
                    "font-family": "Garamond"
                },
                "subtitle": {
                    "text": "Source: http://quotes.wsj.com/index/SPX/historical-prices",
                    "font-weight": "normal"
                },
                "plot": {
                    "aspect": "whisker",
                    "tooltip": {
                        "visible": false
                    },
                    "trend-up": {
                        "line-color": "#0066ff"
                    },
                    "trend-down": {
                        "line-color": "#ff3300"
                    },
                    "preview": {
                        "type": "area", //area (default) or line
                        "line-color": "#0099ff",
                        "background-color": "#0099ff"
                    }
                },
                "scale-x": {
                    "min-value": 1438592400000, //08/03/15
                    "step": "day",
                    "transform": {
                        "type": "date",
                        "all": "%D,<br>%M %d"
                    },
                    "max-items": 10,
                    "item": {
                        "font-size": 10
                    },
                    "zooming": true,
                    //"zoom-to-values":[1448960400000,1454058000000]
                },
                "crosshair-x": {
                    "plot-label": {
                        "text": "Open: $%v0<br>High: $%v1<br>Low: $%v2<br>Close: $%v3",
                        "decimals": 2
                    },
                    "scale-label": {
                        "text": "%v",
                        "transform": {
                            "type": "date",
                            "all": "%D,<br>%M %d, %Y"
                        }
                    }
                },
                "preview": {

                },
                "scale-y": {
                    "values": "1800:2200:100",
                    "format": "$%v",
                    "item": {
                        "font-size": 10
                    },
                    "guide": {
                        "line-style": "solid"
                    }
                },
                "crosshair-y": {
                    "type": "multiple", //"single" (default) or "multiple"
                    "scale-label": {
                        "visible": false
                    }
                },
                "plotarea": {
                    "margin-top": "15%",
                    "margin-bottom": "25%"
                },
                "series": [
                    {
                        "values": dataPoints1
                    }
                ]
            };
//
//            zingchart.render({
//                id: 'myChart',
//                data: myConfig,
//                height: 570,
//            });

            var updateInterval = 5000;
            // initial value
            var yValue1 = 0;
            var yValue2 = 0;

            function in_array(array_sss, text)
            {
                var bool = false;
                $.each(array_sss, function (index, value) {
                    if (value.id == text) {
                        bool = 'true';
                    }
                });
                return bool;
            }

            function updateChart(count) {
                count = count || 1;
                var getCommentCountUrl = $('#myChart').attr("data-chart-url");
                $.ajax({
                    type: "get",
                    url: getCommentCountUrl,
                    success: function (response) {
                        $.each(response.stockInfo.info, function (index, value) {
                            if ((dataPoints1.length > 0 && in_array(dataPoints1, value['id']) == false) || count == 1) {
                                // pushing the new values
                                dataPoints1.push([
                                    value['last_refreshed'],
                                    [value['1. open'], value['2. high'], value['3. low'], value['4. close']]
                                ]);
                                console.log(dataPoints1);
                            }
                        });
                    }
                    ,
                    error: function (e) {

                    }
                }
                );
                // updating legend text with  updated with y Value 
                zingchart.render({
                    id: 'myChart',
                    data: myConfig,
                    height: 570,
                });
            }
            // generates first set of dataPoints 
            updateChart(1);
            setInterval(function () {
//                updateChart(2)
            }, updateInterval);


            setTimeout(function () {
                document.getElementById('myChart-license-text').remove();
            }, 5000);
        }
    </script>-->