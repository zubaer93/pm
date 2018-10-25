<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']); ?>

<div class="container-fluid">
    <div class="row">
        <!-- LEFT -->
        <?= $this->element('Users/sidenav'); ?>
        <!-- RIGHT -->

        <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
            <!-- PERSONAL INFO TAB -->
           <?= $this->element('Symbol/feed'); ?>
            <!-- /PERSONAL INFO TAB -->
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12 mb-30">
            <div class="box-light">
                <div class="box-inner">
                    <ul class="nav nav-tabs nav-top-border" role="tablist">
                        <a class="active" href="#followers" data-toggle="tab" role="tablist"><?= __('Followers') ?></a>
                        <a href="#following" data-toggle="tab" role="tablist"><?= __('Following') ?></a>
                    </ul>
                    <div class="tab-content mt-20">
                        <?= $this->element('Users/board'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <!-- info -->
            <div class="mb-30"><!-- .box-light OR .box-light -->
                <?= $this->element('Watchlist/watchlist', ['showActions' => false, 'showWatchlist' => true]); ?>
            </div>
        </div>
    </div>
</div>
