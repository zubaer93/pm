
<html>
    <head>
        <title>Analysis</title>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', ['fullBase' => true]); ?>
        <?= $this->Html->css('https://www.stockgitter.com/frontend_theme/css/symbol/symbol.css'); ?>
        <?= $this->Html->css('https://www.stockgitter.com/frontend_theme/css/layout.css'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            function forprint() {
                if (!window.print()) {
                    var url = location.origin;
                    window.location.href = url;
                } else {
                    window.print();
                }


            }
            // window.onload=forprint();

            // This code is collected but useful, click below to jsfiddle link.
        </script>
        <style>
            h2
            {
                color:#C03;
                font-size:18px;
                font-family:"Courier New", Courier, monospace;
            }
            p
            {
                font-family:"Courier New", Courier, monospace;
                font-size:15px;
                color:#066;
                text-align:justify;
            }
            a
            {
                color: #337ab7;
                text-decoration:none;
                font-size:20px;
            }
            a[href]:after{
                display: none;
            }


        </style>
    </head>

    <body id="body">



        <div style="width:1000px; border:0px #A514BF dashed; margin:0 auto; padding:10px;">
            <?php
            $price = ($stockInfo['info']['1. open'] - $stockInfo['info']['4. close']);
            ?>
            <h2><?= $user_name; ?></h2>
            <div class="container">
                <div class="row">
                    <div class="column">
                        <div class="clearfix js-price-color mb-35">
                            <div class="company_info">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 info_first_div">
                                        <h5><?= $company_name; ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="symbol-box font-size">
                                <span class="ticker-name "><?= $company_symbol; ?> </span>
                            </div>
                            <div class="ticker-price">
                                <div class="pricing" data-stock-change="<?= $price; ?>">
                                    <span class="price font-size"><?= $this->Number->currency($stockInfo['info']['1. open'], 'USD'); ?></span>
                                    <span class="denomination font-size"></span>
                                    <span class="metric-change">
                                        <span class="change-image <?= $price >= 0 ? 'positive' : 'negative' ?>"></span>
                                        <span class="change font-size <?= $price >= 0 ? 'positive' : 'negative' ?>">
                                            <?= $this->Number->currency($price, 'USD') ?>
                                            <?php if (!empty($stockInfo['info']['1. open']) && $stockInfo['info']['1. open'] > 0): ?>
                                                <?= ' (' . $this->Number->toPercentage(($price) * 100 / $stockInfo['info']['1. open']) . ')'; ?>
                                            <?php else: ?>
                                                <?= ' (' . __("0.00%") . ')'; ?>
                                            <?php endif; ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <span class="exchange"><?= (($currentLanguage == 'JMD') ? __('Delayed') : __('real time')) . ' ' . $stockInfo['date']; ?></span>
                        </div>
                    </div>
                    <div class="column">
                        <?php if (isset($news)): ?>
                            <?php foreach ($news as $val): ?>
                                <div class=""><!-- post -->
                                    <div class="">
                                        <?=
                                        $this->Html->link(
                                                $this->Html->image(App\Model\Service\Core::getImagePath($val['urlToImage']), ['width' => 60]), [
                                            '_name' => 'news',
                                            'slug' => $val['slug'],
                                            '_full' => false
                                                ], [
                                            'escape' => false
                                                ]
                                        );
                                        ?>
                                    </div>
                                    <div class="">
                                        <?=
                                        $this->Html->link($val['title'], [
                                            '_name' => 'news',
                                            'slug' => $val['slug'],
                                            '_full' => true
                                                ], [
                                            'class' => 'tab-post-link'
                                                ]
                                        );
                                        ?>
                                        <small><?= date("F d Y", strtotime($val['publishedAt'])); ?></small>
                                    </div>
                                </div><!-- /post -->
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <div align="center">
                <script>
                    window.onload = function () {
                        var dataPoints1 = [];
                        var dataPoints2 = [];
                        console.log(12142);
                        function chart(data) {


                            Highcharts.chart('chartContainer', {
                                chart: {
                                    zoomType: 'x'
                                },
                                title: {
                                    text: '<?= $company_name; ?>'
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
                                        text: '<?= $company_name; ?>'
                                    }
                                },
                                legend: {
                                    enabled: false
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
                                        animation: false,
                                        type: 'area',
                                        name: '<?= $company_symbol; ?>',
                                        data: data
                                    }]
                            });
                            $("svg").find(".highcharts-credits").html('stockgitter.com');
                            forprint();
                            // CallPrint();
                        }
                        var updateInterval = 15000;
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
                                            // dataPoints1.push([
                                            //      value['id'],
                                            //     new Date(value['last_refreshed']),
                                            //      [value['1. open'], value['2. high'], value['3. low'], value['4. close']]
                                            // ]);
                                            dataPoints2.push([
                                                new Date(value['last_refreshed']).getTime(), // some mock date
                                                yValue2
                                            ]);
                                        }
                                    });

                                    chart(dataPoints2);
                                },
                                error: function (e) {

                                }
                            });
                        }
                        // generates first set of dataPoints
                        updateChart(1);
                        setInterval(function () {
                            // updateChart(2)
                        }, updateInterval);

                    }
                </script>
                <div data-symbol="<?= $company_symbol; ?>"
                     data-chart-url="<?= $this->Url->build(['_name' => 'get_chart_data', 'symbol' => $company_symbol]); ?>"
                     id="chartContainer" style="height: 500px; width: 100%;">
                </div>
            </div>
        </div>

    </body>
</html>
