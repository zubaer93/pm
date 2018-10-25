<script>
    jQuery(window).ready(function () {
        var neg_data =<?= $paramsChart ?>;
        Morris.Line({
            element: '<?= $id ?>',
            data: neg_data,
            xkey: 'period',
            ykeys: 'a',
            labels: 'Price',
            units: '$'
        });

        var myVar<?= $id ?>;

        myVar<?= $id ?> = setTimeout(check, 500);

        function check() {
            if ($('#<?= $id ?>').text() != '') {
                clearTimeout(myVar<?= $id ?>);
                $('.loading<?= $id ?>').remove();
            }
        }
        ;
    });

</script>
<?php
$price = number_format($stockInfo['info']['1. open'] - $stockInfo['info']['4. close'], 2);
?>
<div class="row">
    <div class="col-md-4 col-sm-12 col-lg-4">
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
        <span class="exchange">
            <?= (($currentLanguage == 'JMD') ? __('Delayed') : __('real time')) . ' ' . $stockInfo['date']; ?>
        </span>
        <h4 class="mt-5 mb-14">
            <?= __('Inv Amount: '); ?>
            <d class="font-lato"><?= $this->Number->currency($inv_amount, 'USD') ?></d>
        </h4>
        <p>
            <a class="badge btn-primary letter-spacing-1" href="#"><?= __('Sell '); ?></a>
        </p>
    </div>

    <div class="col-sm-12 col-lg-4 col-md-4">
        <h4 class="mb-0">
            <?= __('Quantity: '); ?>
            <b class="font-lato"><?= $quantity ?></b>
        </h4>
        <h4 class="">
            <?= __('Total Gain/Loss: '); ?>
            <b  class="font-lato <?= $gainLoss >= 0 ? 'positive' : 'negative' ?>"><?= $this->Number->currency($gainLoss, 'USD') ?></b>

        </h4>
        <h4 class="mb-0">
            <?= __('Broker: '); ?>
            <b class="font-lato"><?= $broker ?></b>
        </h4>
        <h4 class="">
            <?= __('Fees: '); ?>
            <b class="font-lato"><?= $this->Number->currency($fees, 'USD') ?></b>
        </h4>
    </div>
    <div class="col-sm-12 col-lg-4 col-md-4">
        <div class="col-lg-12">
            <p></p>
            <h4 class="mb-35 text-red"><?= __('Simulated Trade'); ?></h4>
        </div>
        <div class="col-lg-12">
            <h4>
                <?= __('Value: '); ?>
                <b class="mb-0 font-lato"><?= $this->Number->currency(($total - $fees), 'USD') ?></b>
            </h4>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="loading<?= $id ?>">
            <img class="img-centre" src="/frontend_theme/img/loading-small.gif" alt="Loader" >
        </div>
        <div id="<?= $id ?>"> 
        </div>
    </div>
</div>

