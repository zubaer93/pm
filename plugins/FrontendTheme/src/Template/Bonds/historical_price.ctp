<?= $this->element('FX/table-responsive'); ?>
<section style="padding: 20px">
    <div class="container" data>
        <div class="row">
            <div class="col-12">
                <h2><?= $name; ?></h2>
            </div>
        </div>
        <div class="row">  
            <div class="col-3">
                <h3 class="text-green pull-right"><?= '$' . $price; ?></h3>
                <h3><?= $isinCode; ?></h3>
            </div>

            <div class="col-9">
                <div class="pull-right">
                    <?php if ($hasItem): ?>
                        <?= $this->Form->postLink($this->Html->tag('i', '', ['class' => 'fa fa-remove']) . ' ' . __('Watchlist'), [
                            'controller' => 'watchlist_bonds',
                            'action' => 'removeItem',
                            $isinCode
                        ], [
                            'escape' => false,
                            'confirm' => 'Are you sure to remove this item?',
                            'class' => 'btn btn-danger'
                        ]); ?>
                    <?php else: ?>
                        <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-eye']) . ' ' . __('Watchlist'), 'javascript:;', [
                            'escape' => false,
                            'class' => 'btn btn-success js-watchlist',
                            'data-url' => $this->Url->build(['controller' => 'watchlist_bonds', 'action' => 'addItem', $isinCode])
                        ]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <?= $this->element('Symbol/feed', [
                    'custom_class' => 'slimscroll-367',
                    'overflow' => 'auto'
                ]); ?>

                <div class="row my-3"><?= $this->element('News/news'); ?></div>

                <div class="row">
                    <div class="table-responsive fx-table">
                        <table class="table table-hover table-vertical-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        Historical price data coming soon!
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-30">
                <div class="table-responsive fx-table" data-trader-url="<?= $this->Url->build(['_name' => 'getTraderJs']); ?>" >
                    <table class="table table-hover table-vertical-middle ">
                        <thead>
                            <tr>
                                <th class="text-center" colspan=2>Bond Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bond as $key => $val): ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $val ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Watchlist Bonds</h4>
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

<?php $this->start('script'); ?>
    <?= $this->Html->script('watchlist/watchlist_bond_forex.js'); ?>
    <script type="text/javascript">
        $(function() {
            new Watchlist();
        });
    </script>
<?php $this->end(); ?>