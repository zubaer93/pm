<?= $this->Html->css('new_style/new_style.css'); ?>
<?= $this->Html->script('symbol/script.js', ['block' => 'script']); ?>

<section style="padding: 20px">
    <div class="container-fluid">
        <div class="text-center mt-10 mb-20">
            <h3><?= __('All ' . $currentLanguage . ' Activity'); ?></h3>
        </div>
        <div class="divs">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-sm-12 mb-30 first_div">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 mb-10">
                            <div class="box-light">
                                <div class="box-inner">
                                    <div class="box-messages">
                                        <h4 class="mb-20"><?= __('Sector Performances'); ?></h4>
                                    </div>
                                    <div class="sector-list1">
                                        <ul class="nano-content-sector">
                                            <?php foreach ($sector as $key => $val): ?>
                                                <?php
                                                    $class = 'negative';
                                                    if ((float) $val >= 0) {
                                                        $class = 'positive';
                                                    }
                                                ?>
                                                <li class="wl-item">
                                                    <div class="pricing" data-stock-direction="negative">
                                                        <a href="#">
                                                            <div class="price-container">
                                                                <h2 class="price"><?= $val; ?></h2>
                                                                <span class="change-image-arrow <?= $class; ?>"></span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <a href="#">
                                                        <span class="js-price-color price-color-change <?= $class; ?>"></span>
                                                        <h2><?= $key; ?></h2>
                                                        <span class="symbol-title">&nbsp;</span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 mb-10">
                            <?= $this->element('Symbol/dataTable/stocks'); ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <?= $this->element('Watchlist/watchlist', ['showWatchlist' => true]); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 mb-30 third_div">
                    <?= $this->element('Event/calendar')?>
                    <div class="box-light">
                        <div class="box-inner">
                            <div class="box-messages">
                                <h4 class="mb-20"><?= __('Latest on the ' . $countryName->name . ' Market'); ?></h4>
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
            </div>
        </div>
    </div>
</section>