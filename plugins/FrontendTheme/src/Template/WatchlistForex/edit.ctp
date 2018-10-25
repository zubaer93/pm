<?= $this->Form->create($watchlistForex);?>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= $this->Form->control('name', ['class' => 'form-control', 'placeholder' => 'Watchlist Name']);?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
        <?= $this->Form->button(__('Save changes'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
    </div>
<?= $this->Form->end(); ?>