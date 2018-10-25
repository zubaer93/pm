<div class="box-messages">
    <h4 class="mb-20"><?= __('Financial Statement'); ?></h4>
</div>
<div class="sector-list sector-list3">
    <ul class="nano-content-sector">
        <?php foreach ($statement as $key => $val): ?>
            <?php
            $class = 'positive';
            ?>
            <li class="wl-item">
                <a href="<?= $this->Url->build(['_name' => 'financial_statements_symbol', 'symbol' => $val->company->symbol . '_' . $val->id]) ?>">
                    <span class="js-price-color price-color-change <?= $class; ?>"></span>
                    <h2><?= $val->title; ?></h2>
                    <span class="symbol-title"><?= $val->created_at->nice(); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
