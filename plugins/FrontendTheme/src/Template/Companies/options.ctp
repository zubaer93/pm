<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']);?>

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
        data-options-toggle-url="<?= $this->Url->build(['_name' => 'options', 'stock' => $companyInfo['symbol']], ['fullBase' => true]); ?>">
            
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <?= $this->element('Symbol/stock_options'); ?>
                <?= $this->element('Symbol/feed'); ?>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 mb-10">
                        
                        <div class="box-light mt-10">
                            <div class="box-inner news_div">
                                <div class="box-messages">
                                    <h4 class="mb-20">
                                        <?= $companyInfo['symbol'] . ' ' . __(' Recent News'); ?>
                                    </h4>
                                </div>
                                <?= $this->element('News/news'); ?>

                            </div>
                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More News...'), ['_name' => 'all_news'], [
                                'tabindex' => '-1',
                                'escape' => false,
                                'class' => 'btn btn-sm btn-reveal btn-default float-right'
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-10">
                        <ul class="nav nav-tabs nav-top-border" role="tablist">
                            <a class="active" href="#calls" data-toggle="tab" role="tablist"><?= __('Calls') ?></a>
                            <a href="#puts" data-toggle="tab" role="tablist"><?= __('Puts') ?></a>
                        </ul>
                        <div class="tab-content mt-20">
                            <?= $this->element('Symbol/dataTable/options'); ?>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-10">    
                        <div class="box-light">
                            <div class="box-inner" style="height: 530px;">
                                <?php if ($currentLanguage == 'JMD'): ?>
                                    <?= $this->element('Chart/jmd_chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>
                                <?php else: ?>
                                    <?= $this->element('Chart/chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?= $this->element('Watchlist/watchlist', ['showActions' => false, 'showWatchlist' => true]); ?>
                    
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?= $this->element('Symbol/company_overview/company_overview'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>