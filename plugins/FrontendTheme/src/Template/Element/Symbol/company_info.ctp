<?= $this->Html->meta('og:description', $companyInfo->name);?>
<?= $this->Html->meta('og:title', $companyInfo->name);?>
<?= $this->Html->meta('open', $stockInfo['info']['1. open']);?>
<?= $this->Html->meta('close', $stockInfo['info']['4. close']);?>
<?=$this->Html->meta('currency', $this->Number->currency($stockInfo['info']['1. open'], 'USD'));?>
<?=$this->Html->meta('symbol', $companyInfo->symbol);?>
<div class="row">
    <div class="col-lg-<?= ((!isset($show_analysis) && !isset($show_watch_list_button)) ? 8 : 12); ?> col-md-12 col-sm-12">
        <div class="row">   
            <div class="col-lg-12 col-md-12 col-sm-12 info_second_div">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-10 option_stock">
                        <label class="switch" data-current-language="<?= $currentLanguage; ?>">
                            <input type="checkbox" id="togBtn" class="option_stock_switch">
                            <div class="slider round"></div>
                        </label>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 mb-10 analysis_stock">
                        
                    </div>
                    <?php if (!isset($show_analysis)): ?>
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-10 analysis_stock">
                            <a href="<?= $this->Url->build(['_name' => 'analysis', 'stock' => $companyInfo->symbol]); ?>" class="btn btn-primary btn-sm relative">
                                <i class="fa fa-line-chart fs-20 p-0"></i><?= __(' Analysis'); ?></a>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-6 col-md-12 col-sm-12 mb-10 analysis_stock">
                            <?= $this->element('Symbol/company_info_tools/watch_list_button', ['authUser' => $authUser]); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clearfix js-price-color ticker-header">
            <div class="company_info">
                <div class="row">                   
                    <div class="col-lg-12 col-md-12 col-sm-12 info_first_div">
                        <h1><?= $companyInfo->name; ?></h1>
                    </div>
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
    <?php if (!isset($show_analysis) && !isset($show_watch_list_button)): ?>
        <div class="col-lg-4 col-md-4 col-sm-12 mb-20">
            <?= $this->element('Symbol/company_info_tools/watch_list_button', ['authUser' => $authUser]); ?>
        </div>
    <?php endif; ?>
</div>
