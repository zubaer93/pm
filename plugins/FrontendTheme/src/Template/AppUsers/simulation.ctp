<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']); ?>
<div class="container-fluid mt-10">
    <div class="row">
        <!-- LEFT -->
        <?= $this->element('Users/sidenav'); ?>
        <!-- RIGHT -->
        <div class="col-lg-8 col-md-8 col-sm-7 mb-80">
            <ul class="nav nav-tabs nav-top-border" role="tablist">
                <a class="active" href="#simulation" data-toggle="tab" role="tablist"><?= __('Simulation') ?></a>
                <a href="#watchlists" data-toggle="tab" role="tablist"><?= __('Watchlists') ?></a>
            </ul>

            <div class="tab-content mt-20">
                <!-- SIMULATION TAB -->
                <?= $this->element('Users/simulation'); ?>
                <!-- /SIMULATION TAB -->

                <div class="tab-pane" id="watchlists">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?= $this->element('Watchlist/watchlist', ['showActions' => false, 'showWatchlist' => true]); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>