<?php
$this->Html->script(
        [
    'forex/script.js'
        ], ['block' => 'script']
);
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="clearfix js-price-color ticker-header">
            <h1><?= $exchangeInfo['from_currency_code'] . '-' . $exchangeInfo['to_currency_code']; ?></h1>
            <div class="symbol-box font-size">
                <span class="ticker-name "><?= $exchangeInfo['from_currency_code'] . '-' . $exchangeInfo['to_currency_code']; ?> </span>
            </div>
            <div class="ticker-price">
                <div class="pricing" data-stock-change="<?= $exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate']; ?>">
                    <span class="price font-size">
                        <?= substr($this->Number->currency('', $exchangeInfo['to_currency_code'], []), 0, -4); ?>
                        <span class="exchange_rate">
                            <?= number_format($exchangeInfo['exchange_rate'], 5, '.', ''); ?>
                        </span>
                    </span>
                    <span class="denomination font-size"></span>
                    <span class="metric-change">
                        <span class="change-image"></span>
                        <span class="change font-size">

                            <?php if ($exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate'] > 0 || ($exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate']) * (-1) > 0): ?>
                                <?= substr($this->Number->currency('', $exchangeInfo['to_currency_code'], []), 0, -4) . ' ', number_format($exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate'], 5, '.', ''); ?>
                            <?php else: ?>
                                <?= substr($this->Number->currency('', $exchangeInfo['to_currency_code'], []), 0, -4) . ' ', number_format($exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate'], 2, '.', ''); ?>
                            <?php endif; ?>

                            <?= ' (' . $this->Number->toPercentage(($exchangeInfo['exchange_rate'] - $exchangeInfo['high']['exchange_rate']) * 100 / $exchangeInfo['exchange_rate']) . ')'; ?>
                        </span>
                    </span>
                </div>
            </div>
            <span class="exchange"><?= __('real time') . ' ' . (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s"); ?></span>
        </div>
    </div>
</div>
