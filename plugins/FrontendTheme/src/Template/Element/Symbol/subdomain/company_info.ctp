<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="clearfix js-price-color ticker-header">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label class="switch" data-current-language="<?= $currentLanguage; ?>">
                        <input type="checkbox" id="togBtn" class="option_stock_switch">
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 ">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6 analysis_stock">
                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-line-chart fs-20 p-0']) . ' ' . __('Analysis'), [
                                '_name' => 'analysis',
                                'stock' => $companyInfo->symbol
                            ], [
                                'class' => 'btn btn-sm btn-primary relative comments_post',
                                'data-message-id' => '84',
                                'escape' => false
                            ]); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 analysis_stock">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h1><?= $companyInfo->name; ?></h1>
                </div>
            </div>
            <div class="symbol-box font-size">
                <span class="ticker-name "><?= $companyInfo->symbol; ?> </span>
            </div>
            <div class="ticker-price">
                <div class="pricing" data-stock-change="<?= $stockInfo['info']['1. open'] - $stockInfo['info']['4. close']; ?>">
                    <span class="price font-size"><?= $this->Number->currency($stockInfo['info']['1. open'], 'USD'); ?></span>
                    <span class="denomination font-size"></span>
                    <span class="metric-change">
                        <span class="change-image"></span>
                        <span class="change font-size">
                            <?= $this->Number->currency($stockInfo['info']['1. open'] - $stockInfo['info']['4. close'], 'USD') ?>
                            <?php if (!empty($stockInfo['info']['1. open']) && $stockInfo['info']['1. open'] > 0): ?>
                                <?= ' (' . $this->Number->toPercentage(($stockInfo['info']['1. open'] - $stockInfo['info']['4. close']) * 100 / $stockInfo['info']['1. open']) . ')'; ?>
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
    <?php if (!empty($authUser)) : ?>
        <div class="col-lg-4 col-md-4 col-sm-12 mb-20">
            <div class="add_in_watch_list" data-create-new-watchlist ="<?= $this->Url->build(['_name' => 'createWatchlist']); ?>">
                <div class="btn-price-change button js-price-color" data-symbol="<?= $companyInfo->symbol; ?>" title="Watch <?= $companyInfo->symbol; ?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="select-watch-list-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Add In Group'); ?>
            </div>

            <div class="modal-body">
                <div class='row'>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div style="">
                            <label class="select">
                                <?= $this->Form->control('watch_list', ['required' => true, 'options' => $watchlistStock, 'class' => 'form-control users']); ?>
                            </label>
                        </div>
                    </div>

                    <?php if ($accountType != 'FREE'): ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label class="checkbox float-right mb-10">
                                <input type="checkbox" value="1" class="add_new_group_checkbox">
                                <i></i> <?= __('Add New Watchlist'); ?>
                            </label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="text" name='watch_list_name' class="form-control checked-agree float-right modal-watch-list " placeholder="Enter Watch List Name">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal"><?= __('Cancel'); ?></a>
                <button type="button" class="btn btn-success btn-ok button_save_group"><?= __('Save'); ?></button>
            </div>
        </div>
    </div>
</div>
