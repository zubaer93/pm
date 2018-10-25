<?= $this->Html->css('FrontendTheme.../css/layout-datatables'); ?>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="clearfix js-price-color ticker-header">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="switch">
                        <input type="checkbox" id="togBtn" class="option_stock_switch" checked>
                        <div class="slider round"></div>
                    </label>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h1><?= $companyInfo->name; ?></h1>
                </div>
            </div>
            <div class="symbol-box font-size">
                <span class="ticker-name "><?= $companyInfo->symbol; ?> </span>
            </div>
        </div>
    </div>
    <?php if (!empty($authUser)) : ?>
        <div class="col-lg-4 col-md-4 col-sm-12 mb-20">
            <div class="row">
                <div class="btn-price-change button js-price-color" data-symbol="<?= $companyInfo->symbol; ?>" title="Watch <?= $companyInfo->symbol; ?>"></div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if (isset($options['calls'])): ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
                <?php foreach ($options['calls'] as $key => $val): ?>
                    <?php if ($key <= 1): ?>
                        <div class="col-md-6">
                            <ul class="list-unstyled list-icons">
                                <li><h1><span class="color_black"><?= __('Price : '); ?></span><span class="color_black"> <?= $val['lastPrice']; ?></span></h1></li>
                                <li><h1><span class="color_black"><?= __('Bid : '); ?></span><span class="color_black"> <?= $val['bid']; ?></span></h1></li>
                                <li><h1><span class="color_black"><?= __('Ask : '); ?></span><span class="color_black"> <?= $val['ask']; ?></span></h1></li>
                                <li><h1><span class="color_black"><?= __('Volume : '); ?></span><span class="color_black"> <?= $val['volume']; ?></span></h1></li>
                                <li><h1><span class="color_black"><?= __('Strike : '); ?></span><span class="color_black"> <?= $val['strike']; ?></span></h1></li>
                                <li><h1><span class="color_black"><?= __('Exp : '); ?></span><span class="color_black"> <?= date('Y-m-d', $val['expiration']); ?></span></h1></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>