<div class="container">
    <h2 class="text-center text-black"><?= __('Sector comparison for ') . $symbol ?></h2>
    <div class="row modal_sector_body">
        <div class="col-2 mt-50">
            <p class="main"><?= __('Open Price:'); ?></p>
            <p class="main"><?= __('Volume (units):'); ?></p>
            <p class="main"><?= __('Last Traded Price:'); ?></p>
            <p class="main"><?= __("Today's Range:"); ?></p>
            <p class="main"><?= __("52 Week Range:"); ?></p>
            <p class="main"><?= __("52 Week Ind:"); ?></p>
            <p class="main"><?= __("Bid Price:"); ?></p>
            <p class="main"><?= __("Ask Price:"); ?></p>
            <p class="main"><?= __("Close Net Change:"); ?></p>
            <p class="main"><?= __("Market Change:"); ?></p>
            <p class="main"><?= __("Market Value:"); ?></p>
            <p class="main"><?= __("Shares Outstanding:"); ?></p>
            <p class="main"><?= __("Num Of Trades:"); ?></p>
            <p class="main"><?= __("Pre Dividen Amount:"); ?></p>
            <p class="main"><?= __("Dividend:"); ?></p>
            <p class="main"><?= __("EPS:"); ?></p>
        </div>
        <?php foreach ($options as $val): ?>
            <?php
            $option = $val['stockDetail'];
            $companyInfo = $val['companyInfo'];
            if (count($option)):
                ?>
                <div class="col-2">
                    <div class="sector-list" style="    min-height: auto;">
                        <ul class="nano-sector-content">
                            <li class="wl-item">
                                <div class="pricing">
                                    <a href="<?= $_SERVER['SERVER_NAME'] . $this->Url->build(['_name' => 'symbol', 'stock' => $companyInfo['symbol']]); ?>">
                                        <div class="price-container">
                                            <h2 class="price text-black"><?= $this->Number->currency($option['open']); ?></h2>
                                            <span class="change-image-arrow <?= (($option['open'] - $option['close']) >= 0) ? 'positive' : 'negative'; ?>"></span>
                                        </div>
                                        <span class="change text-black <?= (($option['open'] - $option['close']) >= 0) ? 'positive' : 'negative'; ?>"> <?php
                                            $percentage = $option['open'] == 0 ? 0 :
                                                    ($option['open'] - $option['close']) * 100 / $option['open'];
                                            ?>
                                            <?=
                                            $this->Number->currency($option['open']) . ' ('
                                            . $this->Number->toPercentage($percentage) . ')';
                                            ?> </span>
                                    </a>
                                </div>
                                <a href="<?= $_SERVER['SERVER_NAME'] . $this->Url->build(['_name' => 'symbol', 'stock' => $companyInfo['symbol']]); ?>">
                                    <span class="js-price-color price-color-change <?= (($option['open'] - $option['close']) >= 0) ? 'positive' : 'negative'; ?>">
                                    </span>
                                    <h2 class="text-black"><?= $companyInfo['symbol'] ?></h2>
                                    <span class="symbol-title text-black"><?= $companyInfo['name'] ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <p> <?= $this->Number->currency(isset($option['open']) ? $option['open'] : '0.00') ?></p>
                    <p>  <?= $this->Number->format(isset($option['volume']) ? $option['volume'] : '0.00'); ?></p>
                    <p>  <?= $this->Number->currency(isset($option['last_trade_pice']) ? $option['last_trade_pice'] : '0.00') ?></p>
                    <p>   <?= $this->Number->currency(isset($option['low']) ? $option['low'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['high']) ? $option['high'] : '0.00') ?>
                    </p>
                    <p>   <?= $this->Number->currency(isset($option['fiftyTwoWeekLow']) ? $option['fiftyTwoWeekLow'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['fiftyTwoWeekHigh']) ? $option['fiftyTwoWeekHigh'] : '0.00') ?>
                    </p>
                    <p>    <?= $this->Number->currency(isset($option['lowPrice52Ind']) ? $option['lowPrice52Ind'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['highPrice52Ind']) ? $option['highPrice52Ind'] : '0.00') ?>
                    </p>
                    <p>    <?= $this->Number->currency(isset($option['bidPprice']) ? $option['bidPprice'] : '0.00') ?>
                    </p>
                    <p>    <?= $this->Number->currency(isset($option['askPrice']) ? $option['askPrice'] : '0.00') ?>                            </p>
                    <p>   <span class="change-image-arrow <?php echo((isset($option['closeNetChange']) && (float) $option['closeNetChange'] > 0) ? 'positive' : 'negative'); ?>"></span>
                        <?= $this->Number->toPercentage(isset($option['closeNetChange']) ? $option['closeNetChange'] : '0.00') ?>                            </p>
                    <p>
                        <span class="change-image-arrow <?php echo(( isset($option['closePercentChange']) && (float) $option['closePercentChange'] > 0) ? 'positive' : 'negative'); ?>"></span>
                        <?= $this->Number->toPercentage(isset($option['closePercentChange']) ? $option['closePercentChange'] : '0.00') ?>
                    </p>
                    <p>
                        <?= $this->Number->currency((isset($option['sharesOutstanding']) && isset($option['close'])) ? $option['sharesOutstanding'] : '0.00', 'USD'); ?>
                    </p>
                    <p>
                        <?= $this->Number->format(isset($option['marketCap']) ? $option['marketCap'] : '0.00') ?> units  </p>
                    <p>
                        <?= $this->Number->format((isset($option['numOfTrades'])) ? $option['numOfTrades'] : '0.00') ?> units </p>
                    <p>
                        <?= $this->Number->currency((isset($option['preDividendAmount'])) ? $option['preDividendAmount'] : '0.00') ?>

                    </p>
                    <p>
                        <?= $this->Number->currency((isset($option['dividendDate'])) ? $option['dividendDate'] : '0.00') ?>

                    </p>
                    <p>
                        <?= $this->Number->format((isset($option['eps'])) ? $option['eps'] : '0.00') ?>
                    </p>
                </div>
                <?php
            endif;
        endforeach;
        ?>
    </div>
</div>
