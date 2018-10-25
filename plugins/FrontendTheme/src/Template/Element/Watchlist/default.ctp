<div class="text-muted text-center">
    <?php if (!empty($authUser)) : ?>
        <div class="wl-list wl-list-custom wl-list nano">
            <ul class="nano-content">
            </ul>
        </div>
        <div id="box-watchlist-content">
            <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
            <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
            <p class="fs-12 mb-10">
                <?= __('Add to your watchlist for easy access to your favorite %s', $type); ?>
            </p>
        </div>
    <?php else : ?>
        <div id="box-watchlist-content">
            <?= $this->Html->image("FrontendTheme._smarty/icon-watchlist.png", ["width" => "50%", "alt" => "Watchlist"]); ?>
            <p class="fs-18 mb-6"><b><?= __('Your Watchlist'); ?></b></p>
            <p class="fs-12 mb-10">
                <?= __('Sign up to StockGitter to save a watchlist for easy access to your favorite %s', $type); ?>
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