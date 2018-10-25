<?= $this->Html->css('new_style/new_style.css'); ?>
<?php
    use Cake\Utility\Hash
?>

<?= $this->element('FX/table-responsive'); ?>

<section>
    <div class="container-fluid">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#all" data-toggle="tab">
                        <?= __('All Bonds');?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#corporate" data-toggle="tab">
                        <?= __('Corporate Bonds');?>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="all">
                    <div class="col mb-30">
                        <div class="box-message">
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-30 mt-30">
                                <div class="table-responsive fx-table">
                                    <table class="table table-hover table-vertical-middle js-bond-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('Issuer Name 1'); ?></th>
                                                <th><?= __('ISIN Code'); ?></th>
                                                <th><?= __('Bond Yield'); ?></th>
                                                <th><?= __('Currency'); ?></th>
                                                <th><?= __('Watchlist'); ?></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($allBonds['items'] as $bond): ?>
                                                <tr>
                                                    <td><?= Hash::get($bond, 'issuerNameInBoldLetters'); ?></td>
                                                    <td>
                                                        <?= $this->Html->link(Hash::get($bond, 'ISINCode'), [
                                                            'controller' => 'Bonds',
                                                            'action' => 'historical_price',
                                                            Hash::get($bond, 'ISINCode')
                                                        ]);?>
                                                    </td>
                                                    <td><?= Hash::get($bond, 'bondPrice'); ?></td>
                                                    <td><?= Hash::get($bond, 'currency'); ?></td>
                                                    <td>
                                                        <?php $hasItem = false; ?>
                                                        <?php foreach($watchlistItems as $item): ?>
                                                            <?php if ($item->isin_code == Hash::get($bond, 'ISINCode')): ?>
                                                                <?php $hasItem = true; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>

                                                        <?php if ($hasItem): ?>
                                                            <?= $this->Form->postLink($this->Html->tag('i', '', ['class' => 'fa fa-remove']), [
                                                                'controller' => 'watchlist_bonds',
                                                                'action' => 'removeItem',
                                                                Hash::get($bond, 'ISINCode')
                                                            ], [
                                                                'escape' => false,
                                                                'confirm' => 'Are you sure to remove this item?',
                                                                'class' => 'users'
                                                            ]); ?>
                                                        <?php else: ?>
                                                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-eye']), 'javascript:;', [
                                                                'escape' => false,
                                                                'class' => 'js-watchlist',
                                                                'data-url' => $this->Url->build(['controller' => 'watchlist_bonds', 'action' => 'addItem', Hash::get($bond, 'ISINCode')])
                                                            ]); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="corporate">
                    <div class="col mb-30">
                        <div class="box-message">
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-30 mt-30">
                                <div class="table-responsive fx-table">
                                    <table class="table table-hover table-vertical-middle js-bond-table">
                                        <thead>
                                            <tr>
                                                <th><?= __('Issuer Name 1'); ?></th>
                                                <th><?= __('ISIN Code'); ?></th>
                                                <th><?= __('Bond Yield'); ?></th>
                                                <th><?= __('Currency'); ?></th>
                                                <th><?= __('Watchlist'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($corporateBonds as $bond): ?>
                                                <?php if ($bond->stocks): ?>
                                                    <tr>
                                                        <td><?= $bond->name; ?></td>
                                                        <td>
                                                            <?= $this->Html->link($bond->symbol, [
                                                                'controller' => 'Bonds',
                                                                'action' => 'historical_price',
                                                                $bond->symbol
                                                            ]);?>
                                                        </td>
                                                        <td><?= $bond->stocks['info']['5. volume']; ?></td>
                                                        <td><?= $bond->stocks['info']['4. close']; ?></td>
                                                        <td>
                                                            <?php $hasItem = false; ?>
                                                            <?php foreach($watchlistItems as $item): ?>
                                                                <?php if ($item->isin_code == $bond->symbol): ?>
                                                                    <?php $hasItem = true; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php if ($hasItem): ?>
                                                                <?= $this->Form->postLink($this->Html->tag('i', '', ['class' => 'fa fa-remove']), [
                                                                    'controller' => 'watchlist_bonds',
                                                                    'action' => 'removeItem',
                                                                    $bond->symbol
                                                                ], [
                                                                    'escape' => false,
                                                                    'confirm' => 'Are you sure to remove this item?',
                                                                    'class' => 'users'
                                                                ]); ?>
                                                            <?php else: ?>
                                                                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-eye']), 'javascript:;', [
                                                                    'escape' => false,
                                                                    'class' => 'js-watchlist',
                                                                    'data-url' => $this->Url->build(['controller' => 'watchlist_bonds', 'action' => 'addItem', $bond->symbol])
                                                                ]); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
        loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
            loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

                if (jQuery().dataTable) {
                    var dataTable = $('.js-bond-table').DataTable({
                        language: {
                            searchPlaceholder: "Search records"
                        }
                    });
                    $('.input-xsmall').removeClass('form-control');
                    $('.dataTables_filter').show();
                }
            });
        });
    </script>
<?php $this->end(); ?>