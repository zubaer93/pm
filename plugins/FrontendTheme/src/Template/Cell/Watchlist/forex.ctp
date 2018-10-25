<?php
    use Cake\Utility\Hash;
?>

<?php if (!empty($forex)): ?>
    <?php $class = 'col-xs-12 col-sm-12 col-md-6 col-lg-6 mb-10'; ?>
    <?php if ($all): ?>
        <?php $class = 'col-xs-12 col-sm-12 col-md-3 col-lg-3 mb-10'; ?>
        <?php if ($accountType != 'FREE'): ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'WatchlistForex',
                            'action' => 'add',
                            'plugin' => false
                        ],
                        'class' => 'no-margin js-create-watchlist']); ?>
                        <div class="row">
                            <div class="col-sm-10 col-md-10 col-lg-10">
                                <div class="form-group">
                                    <?= $this->Form->control('name', ['required' => true, 'label' => false, 'class' => 'form-control js-item-watchlist-name', 'placeholder' => 'Enter forex watchlist name']); ?>
                                </div>
                            </div>

                            <div class="col-sm-2 col-md-2 col-lg-2">
                                <?= $this->Form->button($this->Html->tag('i', '', ['class' => 'fa fa-id-card-o']) . __('Create watch list'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'escape' => false
                                ]);?>
                            </div>
                        </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 mb-10">
            <div class="row js-watchlist-items">
                <?php foreach ($forex as $key => $item): ?>
                    <div class="<?= $class; ?>">
                        <div class="box-light">
                            <div class="box-inner">
                                <div class="box-messages">
                                    <h4 class="mb-20"><?= __($item->name); ?>
                                        <?php if (!$all): ?>
                                            <a href="#" class="float-right" tabindex="-1">
                                                <b class="list-popover" data-container="body"><i class="fa fa-info-circle"></i></b>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($all && !$item->is_default): ?>
                                            <?= $this->Form->postLink($this->Html->tag('i', '', ['class' => 'fa fa-remove negative']), [
                                                'controller' => 'watchlist_forex',
                                                'action' => 'delete',
                                                $item->id
                                            ], [
                                                'escape' => false,
                                                'confirm' => 'Are you sure to remove this item?',
                                                'class' => 'float-right mr-5'
                                            ]); ?>

                                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-cog']), 'javascript:;', [
                                                'escape' => false,
                                                'class' => 'float-right mr-5 js-watchlist',
                                                'data-url' => $this->Url->build(['controller' => 'watchlist_forex', 'action' => 'edit', $item->id])
                                            ]); ?>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="text-muted text-center">
                                    <?php if (!empty($authUser)): ?>
                                        <?php if ($item->watchlist_forex_items): ?>
                                            <?php foreach ($item->watchlist_forex_items as $item): ?>
                                                <div class="wl-list-custom wl-list watchlist-box">
                                                    <ul class="nano-content">
                                                        <li class="wl-item">
                                                            <div class="pricing">
                                                                <a href="<?= $this->Url->build(['_name' => 'forex_currency', 'currency' => $item->trader->from_currency_code . '-' . $item->trader->to_currency_code]);?>">
                                                                    <div class="price-container">
                                                                        <h2 class="price"><?= number_format($item->trader->exchange_rate, 5); ?></h2>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <a href="<?= $this->Url->build(['_name' => 'forex_currency', 'currency' => $item->trader->from_currency_code . '-' . $item->trader->to_currency_code]);?>">
                                                                <h2 style="line-height: 18px;"><?= $item->trader->from_currency_code . '/' . $item->trader->to_currency_code; ?></h2>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div id="box-watchlist-content">
                                                <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                                                <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                                                <p class="fs-12 mb-10">
                                                    <?= __('Add to your watchlist for easy access to your favorite forex'); ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <div id="box-watchlist-content">
                                            <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                                            <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                                            <p class="fs-12 mb-10">
                                                <?= __('Sign up to StockGitter to save a watchlist for easy access to your favorite forex'); ?>
                                            </p>
                                            <?= $this->Html->link(__('SIGN UP'), ['_name' => 'register'], ['class' => 'btn btn-info']); ?>
                                            <div class="sign-in fs-12">
                                                <span>
                                                    <?= $this->Html->link(__('or login'), ['_name' => 'login'], ['class' => 'btn btn-link fs-12']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?= $this->element('Watchlist/default', ['type' => 'forex']); ?>
<?php endif; ?>