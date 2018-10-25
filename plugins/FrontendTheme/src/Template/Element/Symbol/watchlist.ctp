<div class="box-light ticker-container"
     data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
     data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
     data-watchlist-all-url="<?= $this->Url->build(['_name' => 'getWatchlistAll']); ?>"
     data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
     data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>">
    <div class="box-inner">
        <div class="box-messages">
            <h4 class="mb-20"><?= __('WATCH LIST') ?></h4>
        </div>
        <div class="text-muted text-center box-inner">
            <?php if (!empty($authUser)) : ?>
                <div class="wl-list nano">
                    <ul class="nano-content">
                    </ul>
                </div>
                <div id="box-watchlist-content">
                    <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                    <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                    <p class="fs-12 mb-10">
                        <?= __('Add to your watchlist for easy access to your favorite stocks') ?>
                    </p>
                </div>
            <?php else : ?>
                <div id="box-watchlist-content">
                    <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                    <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                    <p class="fs-12 mb-10">
                        <?= __('Sign up to StockGitter to save a watchlist for easy access to your favorite stocks') ?>
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