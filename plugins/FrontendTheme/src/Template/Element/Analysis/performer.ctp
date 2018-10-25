<div class="text-muted text-center box-inner">
    <div class="sector-list nano industry_ul wl-list-custom">
        <ul class="nano-industry-content">
            <?php foreach ($data as $val): ?>
                <li class="wl-item" data-symbol="<?= $val->symbol; ?>">
                    <div class="pricing" data-symbol="<?= $val->symbol; ?>" data-stock-direction="<?= ((-1) * $val->price_change >= 0) ? 'positive' : 'negative' ?>">
                        <a href="<?=
                        $this->Url->build([
                            '_name' => 'symbol',
                            'stock' => $val->symbol,
                        ]);
                        ?>">
                            <div class="price-container">
                                <h2 class="price"><?= $this->Number->currency($val->open, 'USD'); ?></h2>
                                <span class="change-image-arrow <?= ((-1) * $val->price_change >= 0) ? 'positive' : 'negative' ?>"></span>
                            </div>
                            <span class="change <?= ((-1) * $val->price_change >= 0) ? 'positive' : 'negative' ?>"><?= $this->Number->currency((-1) * $val->price_change, 'USD'); ?> 
                                <?php if (!empty($val->open) && $val->open > 0): ?>
                                    <?= ' (' . $this->Number->toPercentage(($val->price_change) * 100 / $val->open) . ')'; ?>
                                <?php else: ?>
                                    <?= ' (' . __("0.00%") . ')'; ?>
                                <?php endif; ?>
                            </span>
                        </a>
                    </div>
                    <a href="<?=
                        $this->Url->build([
                            '_name' => 'symbol',
                            'stock' => $val->symbol,
                        ]);
                        ?>">
                        <span class="js-price-color price-color-change <?= ((-1) * $val->price_change >= 0) ? 'positive' : 'negative' ?>" data-symbol="<?= $val->symbol; ?>"></span>
                        <h2><?= $val->symbol; ?></h2>
                        <span class="symbol-title"><?=(isset($val->company->name)?$val->company->name:' '); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>