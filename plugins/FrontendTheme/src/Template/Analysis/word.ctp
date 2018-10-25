
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?php echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', ['fullBase' => true]);?>
    <?= $this->Html->css('https://www.stockgitter.com/frontend_theme/css/symbol/symbol.css'); ?>
    <?= $this->Html->css('https://www.stockgitter.com/frontend_theme/css/layout.css'); ?>
    <title>My Analysis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        /* Create three equal columns that floats next to each other */
        .column {
            float: left;
            width: 30.33%;
            padding: 10px;
            height: 300px; /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .tab-post {
      padding-bottom: 20px;
      margin: 0 0 20px 0;
      border-bottom: rgba(0,0,0,0.06) 1px solid;
      float: right;
      }
    </style>

    <script>
        /* HTML to Microsoft Word Export Demo
 * This code demonstrates how to export an html element to Microsoft Word
 * with CSS styles to set page orientation and paper size.
 * Tested with Word 2010, 2013 and FireFox, Chrome, Opera, IE10-11
 * Fails in legacy browsers (IE<10) that lack window.Blob object
 */


        (function () {
            "use strict";

            // The initialize event handler must be run on each page to initialize Office JS.
            // You can add optional custom initialization code that will run after OfficeJS
            // has initialized.
            Office.initialize = function (reason) {
                // The reason object tells how the add-in was initialized. The values can be:
                // inserted - the add-in was inserted to an open document.
                // documentOpened - the add-in was already inserted in to the document and the document was opened.

                // Checks for the DOM to load using the jQuery ready function.
                $(document).ready(function () {
                    // Set your optional initialization code.
                    // You can also load saved settings from the Office object.
                });
            };

            // Run a batch operation against the Word JavaScript API object model.
            // Use the context argument to get access to the Word document.
            Word.run(function (context) {

                // Create a proxy object for the document.
                var thisDocument = context.document;
                // ...
            })
        })();

        window.onload = function () {
            var dataPoints1 = [];
            var dataPoints2 = [];
            function chart(data) {


                Highcharts.chart('chartContainer', {
                    chart: {
                        zoomType: 'x'
                    },
                    title: {
                        text: '<?=$company_name;?>'
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
                            text: '<?=$company_name;?>'
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
                        name: '<?=$company_symbol;?>',
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

            function forprint() {
                if (!window.print()){
                    var url =location.origin;
                    window.location.href = url;
                }else{
                    window.print();
                }

            }

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
</head>




<body>
<?php
$price = ($stockInfo['info']['1. open'] - $stockInfo['info']['4. close']);
?>
<!--<button onclick="ff()">Export</button> Click to open table in Microsoft Word-->
<h2><?=$user_name;?></h2>
<div class="container" id="docx">
    <div class="row">
        <div class="column">
            <div class="clearfix js-price-color ticker-header">
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
                        <span class="change-image <?= $price>=0 ? 'positive':'negative'?>"></span>
                        <span class="change font-size <?= $price>=0 ? 'positive':'negative'?>">
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
                  <h3><?= $analysis['name']; ?></h3>

            </div>
        </div>
        <div class="column">
            <?php foreach ($news as $val): ?>
                <div class="row tab-post"><!-- post -->
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <?=
                        $this->Html->link(
                            $this->Html->image(App\Model\Service\Core::getImagePath($val['urlToImage']), ['width' => 60,'height'=>60]), [
                            '_name' => 'news',
                            'slug' => $val['slug'],
                            '_full' => true
                        ], [
                                'escape' => false
                            ]
                        );
                        ?>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12">
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
                <div data-symbol="<?= $company_symbol; ?>"
                     data-chart-url="<?= $this->Url->build(['_name' => 'get_chart_data', 'symbol' => $company_symbol]); ?>"
                     id="chartContainer" style="height: 500px; width: 100%;">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="box-messages">
            <h4 class="mb-20"><?= __('Financial Statement'); ?></h4>
        </div>
        <div class="sector-list sector-list3">
            <ul class="nano-content-sector">
                <?php foreach ($statement as $key => $val): ?>
                    <?php
                    $class = 'positive';
                    ?>
                    <li class="wl-item">
                        <a href="<?= $this->Url->build(['_name' => 'financial_statements_symbol', 'symbol' => $val['company']['symbol'] . '_' . $val['id']]) ?>">
                            <span class="js-price-color price-color-change <?= $class; ?>"></span>
                            <h2><?= $val['title']; ?></h2>
                            <span class="symbol-title"><?= $val['created_at']->nice(); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
