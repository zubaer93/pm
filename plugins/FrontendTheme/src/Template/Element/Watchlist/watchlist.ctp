<?php if (!isset($all)): ?>
    <?php $all = false;?>
<?php endif; ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-10">
    <div class="box-light">
        <div class="box-inner">
            <div class="box-messages">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-10">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#stocks" data-toggle="tab">
                                <?= __('Stocks');?>
                            </a>
                        </li>
                        <?php if ($accountType != 'FREE'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#bonds" data-toggle="tab">
                                    <?= __('Bonds');?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#forex" data-toggle="tab">
                                <?= __('Forex');?>
                            </a>
                        </li>
                    </ul>

                    <?php $class = 'col-sm-12 col-md-6 col-lg-6 mb-10'; ?>
                    <?php if ($all): ?>
                        <?php $class = 'col-sm-12 col-md-3 col-lg-3 mb-10'; ?>
                    <?php endif;?>

                    <div class="tab-content">
                        <div class="tab-pane active" id="stocks">
                            <?php if (!$stockWatchlists->isEmpty()): ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-10">
                                        <div class="row watchlist_list" data-create-new-watchlist ="<?= $this->Url->build(['_name' => 'createWatchlist']); ?>" data-watchlist-edit-url="<?= $this->Url->build(['_name' => 'editWatchlist']); ?>">
                                            <?php if ($all): ?>
                                                <?php if($accountType != 'FREE'): ?>
                                                    <div class="col-sm-10 col-md-10 col-lg-10">
                                                        <input type="text" class="form-control float-right watch_list_name" required="required" placeholder="Enter Watch List Name">
                                                    </div>

                                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                                        <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-id-card-o']) . __('Create watch list'), '#', [
                                                            'class' => 'btn btn-primary create_watch_list',
                                                            'tabindex' => '-1',
                                                            'escape' => false
                                                        ]);?>
                                                    </div>
                                                <?php endif;?>
                                            <?php endif;?>

                                            <?php foreach ($stockWatchlists as $watchlist): ?>
                                                <?php if (!$all): ?>
                                                    <?php $showActions = false; ?>
                                                <?php endif; ?>
                                                <?php if ($watchlist->is_default): ?>
                                                    <?php $showActions = false; ?>
                                                <?php endif; ?>
                                                <div class="<?= $class; ?>">
                                                    <?= $this->element('Watchlist/stocks', ['group' => $watchlist->id, 'group_name' => $watchlist->name, 'showActions' => $showActions, 'showWatchlist' => $showWatchlist]); ?>
                                                </div>
                                                <?php $showActions = true; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?= $this->element('Watchlist/default', ['type' => 'stocks']); ?>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane" id="bonds">
                            <?= $this->cell('FrontendTheme.Watchlist::bonds', ['all' => $all]);?>
                        </div>
                        <div class="tab-pane" id="forex">
                            <?= $this->cell('FrontendTheme.Watchlist::forex', ['all' => $all]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
