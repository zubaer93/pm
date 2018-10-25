<?= $this->Form->create($watchlistItem, ['class' => 'js-add-watchlist-form', 'data-url-group' => $this->Url->build(['controller' => 'WatchlistForex', 'action' => 'add'])]);?>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-10">
                <?= $this->Form->control('watchlist_forex_id', ['options' => $watchlistForex, 'class' => 'form-control js-watchlist-select', 'label' => 'Choice your watchlist']); ?>
            </div>

            <?php if ($accountType != 'FREE'): ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-10">
                    <label class="checkbox float-right">
                        <input type="checkbox" value="1" class="js-new-watchlist">
                        <i></i>Add New Watchlist
                    </label>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="hide js-name">
                        <?= $this->Form->control('name', ['class' => 'form-control', 'placeholder' => 'Watchlist Name']);?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
        <?= $this->Form->button(__('Save changes'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
    </div>
<?= $this->Form->end(); ?>