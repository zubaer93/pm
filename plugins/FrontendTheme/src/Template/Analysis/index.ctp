<?php $this->start('script'); ?>
    <?= $this->Html->script(['symbol/script.js', 'analysis/sector_industry.js']); ?>
<?php $this->end(); ?>

<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container-fluid ticker-container"
        data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
        data-user-id="<?= $userId; ?>"
        data-company-id="<?= $companyInfo['id']; ?>"
        data-symbol="<?= $companyInfo['symbol']; ?>"
        data-sector-modal-url="<?= $this->Url->build(['_name' => 'get_modal_sector', 'symbol' => $companyInfo['symbol']]); ?>"
        data-sector-url="<?= $this->Url->build(['_name' => 'get_sector', 'symbol' => $companyInfo['symbol']]); ?>"
        data-industry-url="<?= $this->Url->build(['_name' => 'get_industry', 'symbol' => $companyInfo['symbol']]); ?>"
        data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
        data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
        data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>"
        data-stock-toggle-url="<?= $this->Url->build(['_name' => 'symbol', 'stock' => $companyInfo['symbol']], ['fullBase' => true]); ?>"
        data-options-toggle-url="<?= $this->Url->build(['_name' => 'options', 'stock' => $companyInfo['symbol']], ['fullBase' => true]); ?>">

        <div class="row">
            <div class="col-lg-5 col-md-6 col-sm-12 mb-10 first_div company_info_max">
                <div class="parent_post">
                    <?= $this->element('Symbol/company_info', ['show_analysis' => false]); ?>
                    
                    <?= $this->element('Symbol/feed', ['custom_class' => 'slimscroll-367']); ?>
                    
                    <?php if ($currentLanguage == 'JMD'): ?>
                        <div class="box-light mt-20">
                            <div class="box-inner">
                                <?= $this->element('Event/event_news', ['events' => $events, 'symbol' => $companyInfo['symbol']]); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($currentLanguage == 'JMD'): ?>
                    <div class="box-light mt-20">
                        <div class="box-inner">
                            <?= $this->element('FinancialStatement/list', ['statement' => $statement]); ?> 
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-7 col-md-6 col-sm-12 mb-10 second_div">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <?= $this->element('Symbol/subdomain/sidenav'); ?>
                    </div>
                    <?php if ($currentLanguage == 'JMD'): ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-20">
                            <div class="box-light">
                                <div class="box-inner">
                                    <div class="box-messages">
                                        <h4 class="mb-20"><?= __('TIME & SALES'); ?></h4>
                                        <h4><?= $this->Number->currency($stockInfo['info']['1. open'], 'USD') ?></h4>
                                    </div>
                                    <?= $this->element('Analysis/timeandsales', ['symbol' => $companyInfo['symbol']]); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 mt-20">
                            <div class="box-light">
                                <div class="box-inner">
                                    <div class="box-messages">
                                        <h4 class="mb-20">RATINGS</h4>
                                    </div>
                                    <div class="table-responsive" style="min-height: 244px; max-height: 244px;">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?= __('Broker Name'); ?></th>
                                                    <th><?= __('Status'); ?></th>
                                                    <th><?= __('Date'); ?></th>
                                                </tr>
                                            </thead>    
                                            <tbody>
                                                <?php foreach ($brokers_details as $val): ?>
                                                    <tr>
                                                        <td><?= $val['broker']['first_name']; ?></td>
                                                        <td><span class="change-image-arrow <?= ((strtolower($val['status']) == "buy") ? 'positive' : 'negative') ?>"> </span> <?= $val['status']; ?></td>
                                                        <td><?= $val['created_at']->nice(); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="box-light mt-20">
                                    <div class="box-inner">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#sector_tab" role="tab" data-toggle="tab"><?= __('SECTOR - ') ?> <?= strtoupper($companyInfo['sector']); ?> 
                                                    <?php if (!is_null($sector_change)): ?>
                                                        <?= $sector_change; ?>
                                                        <span class="change-image-arrow <?= ((int) $sector_change > 0) ? 'positive' : 'negative'; ?>"></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                            <?php if ($currentLanguage == 'JMD'): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#top_performer_tab" role="tab" data-toggle="tab"><?= __('TOP') ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#worst_performer_tab" role="tab" data-toggle="tab"><?= __('WORST') ?></a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active show" id="sector_tab">
                                                <?= $this->element('Analysis/sector'); ?>
                                                <?php if ($currentLanguage == 'JMD'): ?>
                                                    <?php if ($accountType != 'FREE'): ?>
                                                        <button type="button" class="btn btn-sm btn-reveal btn-default sector_modal" data-toggle="modal" data-target="#myModal"><?= __('Details') ?></button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-reveal btn-default" data-toggle="modal" data-target=".bs-subscription-modal-full">
                                                            <?= __('Details') ?>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($currentLanguage == 'JMD'): ?>
                                                <div role="tabpanel" class="tab-pane fade" id="top_performer_tab">
                                                    <?= $this->element('Analysis/top'); ?>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="worst_performer_tab">
                                                    <?= $this->element('Analysis/worst'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="box-light mt-20">
                                    <div class="box-inner">
                                        <div class="box-messages ">
                                            <h4 class="mb-20">
                                                <?= $companyInfo['symbol'] . ' ' . __(' Recent News'); ?>
                                            </h4>
                                        </div>
                                        <div class="sector-list mb-30">
                                            <?= $this->element('News/news'); ?>
                                        </div>

                                        <?php if (in_array($accountType, ['PROFESSIONAL', 'EXPERT'])): ?>
                                            <a href="https://stockgitterintelligence.mybluemix.net/" target="_blank" class="btn btn-sm btn-reveal btn-default"><?= __('Intelligence');?></a>
                                        <?php else: ?>
                                            <a href="/USD/subscribe" tabindex="-1" class="btn btn-sm btn-reveal btn-default" data-toggle="modal" data-target=".bs-subscription-modal-full">
                                                Intelligence</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($currentLanguage == 'JMD'): ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="box-light mt-20">
                                <div class="box-inner">
                                    <?= $this->element('Analysis/details', ['option' => $option, 'symbol' => $companyInfo['symbol']]); ?>
                                </div>
                            </div>
                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More'), 'javascript:;', [
                                'tabindex' => '-1',
                                'escape' => false,
                                'class' => 'btn btn-sm btn-reveal btn-default float-right',
                                'data-toggle' => 'modal',
                                'data-target' => '.bs-example-modal-full'
                            ]);?>
                            <?= $this->element('FinancialStatement/dataTable', ['symbol' => $companyInfo['symbol'], 'partialJS' => true]); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($authUser): ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?= $this->element('Analysis/analysis_tools', ['symbol' => $companyInfo['symbol']]); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- TradingView Widget END -->
<div data-notify="container" id="alert-success-watchlist" class="col-sm-2 col-lg-2 col-md-2 alert btn-primary alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-full">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body sector_modal_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>