<div class="box-light ticker-container watchlist_div"
     data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
     data-group-id="<?= $group; ?>"
     data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
     data-watchlist-all-url="<?= $this->Url->build(['_name' => 'getWatchlistAll']); ?>"
     data-watchlist-group-url="<?= $this->Url->build(['_name' => 'get_watchlist_group', 'group_id' => $group]); ?>"
     data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
     data-watchlist-page-url="<?= $this->Url->build(['_name' => 'watchlist_all']); ?>"
     data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>">
    <div class="box-inner">
        <div class="box-messages">
            <h4 class="mb-20"><?= __($group_name); ?>
                <?php if ($showWatchlist): ?>
                    <a href="#" class="float-right" tabindex="-1">
                        <b class="list-popover" data-container="body"><i class="fa fa-info-circle"></i></b>
                    </a>
                <?php endif; ?>
                <?php if ($showActions): ?>
                    <a href="#" class="float-right mr-5" data-toggle="modal" data-target="#confirm-delete" data-url="<?= $this->Url->build(['_name' => 'watchlist_delete', 'id' => $group]); ?>" tabindex="-1">
                        <i class="fa fa-remove negative"></i>
                    </a>
                    <a href="#" class="float-right mr-5" data-toggle="modal" data-id ="<?= $group ?>" data-name="<?= __($group_name); ?>" data-target="#setting-watch-list" data-url="<?= $this->Url->build(['_name' => 'watchlist_delete', 'id' => $group]); ?>" tabindex="-1">
                        <i class="fa fa-cog"></i>
                    </a>
                </h4>
            <?php endif; ?>
        </div>
        <div class="text-muted text-center">
            <?php if (!empty($authUser)) : ?>
                <div class="wl-list wl-list-custom js-wl-list js-wl-list-<?= $group; ?> nano watchlist-box">
                    <ul class="nano-content group-<?= $group; ?>">
                    </ul>
                </div>
                <div id="box-watchlist-content<?= $group; ?>">
                    <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
                    <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
                    <p class="fs-12 mb-10">
                        <?= __('Add to your watchlist for easy access to your favorite stocks') ?>
                    </p>
                </div>
            <?php else : ?>
                <div id="box-watchlist-content<?= $group; ?>">
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
