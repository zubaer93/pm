<?php
$this->Html->script(
        [
    'symbol/watchlist.js'
        ], ['block' => 'script']
);
?>
<section style="padding: 20px">
    <div class="container-fluid ticker-container"
         data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
         data-user-id="<?= $userId; ?>"
         data-company-id=""
         data-trader-id="<?= $exchangeInfo['id']; ?>"
         data-trader-symbol="<?= $exchangeInfo['from_currency_code'] . '-' . $exchangeInfo['to_currency_code']; ?>"
         data-symbol=""
         data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
         data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
         data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>">
        <div class="divs">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 mb-30 first_div">
                    <div class="box-light">
                        <div class="box-inner">
                            <div class="box-messages">
                                <h4 class="mb-20">
                                    <?= $exchangeInfo['from_currency_code'] . '-' . $exchangeInfo['to_currency_code'] . ' ' . __(' Recent News'); ?>
                                </h4>
                            </div>
                            <?= $this->element('News/news'); ?>
                        </div>
                    </div>
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More News...'), ['_name' => 'all_news'], [
                        'tabindex' => '-1',
                        'escape' => false,
                        'class' => 'btn btn-sm btn-reveal btn-default float-right'
                            ]
                    );
                    ?>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mb-30 second_div">
                    <?= $this->element('Symbol/trader_info'); ?>
                    <?= $this->element('Symbol/feed'); ?>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 mb-30 third_div">
                     <?= $this->element('Symbol/watchlist'); ?>
                </div>
                
            </div>
        </div>
    </div>
</section>