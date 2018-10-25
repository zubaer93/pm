<section style="padding: 20px">
    <div class="container-fluid">
        <div class="heading-title heading-line-single text-center mt-10 mb-20">
            <h3><?= __('All Watch list'); ?></h3>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-10">
                <?= $this->Flash->render(); ?>
            </div>
        </div>

        <?= $this->element('Watchlist/watchlist', ['all' => true, 'showActions' => true, 'showWatchlist' => false]); ?>

    </div>
</section>

<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit</h4>
            </div>

            <div class="js-modal-content">
                <div class="modal-body">
                    <?= $this->Html->image('FrontendTheme._smarty/loaders/1.gif', ['class' => 'center']); ?>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>

<div class="hide js-modal-loader">
    <div class="modal-body">
        <?= $this->Html->image('FrontendTheme._smarty/loaders/1.gif', ['class' => 'center']); ?>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Are you sure you want to delete?'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?= __('Delete'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="setting-watch-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Edit'); ?>
            </div>
            <form action="" class='modal_watch_list_form'>
                <div class="modal-body">
                    <input type="text" name='watch_list_name' class="form-control float-right modal-watch-list" placeholder="Enter Watch List Name">
                    <input type="hidden" name='id' class='hidden_modal_id'>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal"><?= __('Cancel'); ?></a>
                    <button type="submit" class="btn btn-success btn-ok"><?= __('Save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->start('script'); ?>
    <?= $this->Html->script(['symbol/script.js']); ?>
    <?= $this->Html->script('watchlist/watchlist_bond_forex.js'); ?>

    <script type="text/javascript">
        $(function() {
            new Watchlist();
        });
        $('#confirm-delete').on('show.bs.modal', function (e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('url'));
            $(this).find('.modal-body').text($(e.relatedTarget).data('name'));
        });

        $('#setting-watch-list').on('show.bs.modal', function (e) {
            $(this).find('.modal-watch-list').val($(e.relatedTarget).data('name'));
            $(this).find('.hidden_modal_id').val($(e.relatedTarget).data('id'));
            $(this).find('.modal_watch_list_form').attr('action',$('.watchlist_list').data('watchlist-edit-url'));
        });
    </script>
<?php $this->end(); ?>
