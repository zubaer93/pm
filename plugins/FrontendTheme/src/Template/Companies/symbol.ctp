<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']); ?>
<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container-fluid ticker-container"
         data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
         data-user-id="<?= $userId; ?>"
         data-company-id="<?= $companyInfo['id']; ?>"
         data-symbol="<?= $companyInfo['symbol']; ?>"
         data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
         data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
         data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>"
         data-stock-toggle-url="<?= $this->Url->build(['_name' => 'symbol', 'stock' => $companyInfo['symbol']], ['fullBase' => true]); ?>"
         data-options-toggle-url="<?= $this->Url->build(['_name' => 'options', 'stock' => $companyInfo['symbol']], ['fullBase' => true]); ?>"
         >
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6 mb-10">
                <?= $this->element('Symbol/subdomain/company_info'); ?>
                <?= $this->element('Symbol/subdomain/sidenav'); ?>
                <div class="box-light mt-10">
                    <div class="box-inner news_div">
                        <div class="box-messages">
                            <h4 class="mb-20">
                                <?= $companyInfo['symbol'] . ' ' . __(' Recent News'); ?>
                            </h4>
                        </div>
                        <?= $this->element('News/news'); ?>

                    </div>
                </div>
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More News...'), ['_name' => 'all_news'], [
                    'tabindex' => '-1',
                    'escape' => false,
                    'class' => 'btn btn-sm btn-reveal btn-default float-right'
                ]);?>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <?= $this->element('Watchlist/watchlist', ['showActions' => false, 'showWatchlist' => true]); ?>
                    
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-10">
                        <?= $this->element('Symbol/company_overview/company_overview'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div data-notify="container" id="alert-success-watchlist" class="col-sm-2 col-lg-2 col-md-2 alert btn-primary alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>

<div data-notify="container" id="alert-danger-options" class="col-sm-3 col-lg-3 col-md-3 alert btn-danger alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>