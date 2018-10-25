<div class="box-messages">
    <h4 class="mb-20"><?= $symbol.' - Stock Summary';?></h4>
</div>
<div class="sector-list sector-list2">
    <div class="table-responsive sector-details no-overflow">
        <table class="table table-bordered table-striped table-vertical-middle">
            <tbody>
                <tr>
                    <td><b><?= __("Open Price:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['open']) ? $option['open'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Volume Traded (units):"); ?></b></td>
                    <td>
                        <?= $this->Number->format(isset($option['volume']) ? $option['volume'] : '0.00'); ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Last Traded Price:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['last_trade_pice']) ? $option['last_trade_pice'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Today's Range:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['low']) ? $option['low'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['high']) ? $option['high'] : '0.00') ?>

                    </td>
                </tr>
                <tr>
                    <td><b><?= __("52 Week Range:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['fiftyTwoWeekLow']) ? $option['fiftyTwoWeekLow'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['fiftyTwoWeekHigh']) ? $option['fiftyTwoWeekHigh'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("52 Week Ind:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['lowPrice52Ind']) ? $option['lowPrice52Ind'] : '0.00') ?>
                        to
                        <?= $this->Number->currency(isset($option['highPrice52Ind']) ? $option['highPrice52Ind'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Bid Price:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['bidPprice']) ? $option['bidPprice'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Ask Price:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency(isset($option['askPrice']) ? $option['askPrice'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Close Net Change:"); ?></b></td>
                    <td>
                         <span class="change-image-arrow <?php echo((isset($option['closeNetChange']) && (float)$option['closeNetChange'] > 0)?'positive':'negative'); ?>"></span>
                        <?= $this->Number->toPercentage(isset($option['closeNetChange']) ? $option['closeNetChange'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Market Change:");?></b></td>
                    <td>
                        <span class="change-image-arrow <?php echo(( isset($option['closePercentChange']) && (float)$option['closePercentChange'] > 0)?'positive':'negative'); ?>"></span>
                        <?= $this->Number->toPercentage(isset($option['closePercentChange']) ? $option['closePercentChange'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Market Value:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency((isset($option['sharesOutstanding']) && isset($option['close'])) ? $option['sharesOutstanding'] : '0.00', 'USD'); ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Shares Outstanding:"); ?></b></td>
                    <td>
                        <?= $this->Number->format(isset($option['marketCap']) ? $option['marketCap'] : '0.00') ?> units
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Num Of Trades:"); ?></b></td>
                    <td>
                        <?= $this->Number->format((isset($option['numOfTrades'])) ? $option['numOfTrades'] : '0.00') ?> units
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Pre Dividen Amount:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency((isset($option['preDividendAmount'])) ? $option['preDividendAmount'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("Dividend:"); ?></b></td>
                    <td>
                        <?= $this->Number->currency((isset($option['dividendDate'])) ? $option['dividendDate'] : '0.00') ?>
                    </td>
                </tr>
                <tr>
                    <td><b><?= __("EPS:"); ?></b></td>
                    <td>
                        <?= $this->Number->format((isset($option['eps'])) ? $option['eps'] : '0.00') ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>