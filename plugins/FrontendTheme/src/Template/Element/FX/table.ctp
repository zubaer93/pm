<?php
    use Cake\Utility\Hash
?>

<?= $this->element('FX/table-responsive'); ?>
<h2 class="text-center"><?= __('Forex and Crypto Currencies') ?></h2>
<div class="table-responsive fx-table" data-trader-url="<?= $this->Url->build(['_name' => 'getTraderJs']); ?>" >
    <table class="table table-hover table-vertical-middle ">
        <thead>
        <tr>
            <th class="text-align-right"><?= __('Trader instrument') ?> </th>
            <th class="text-align-right"><?= __('Current price') ?></th>
            <th class="text-align-right">
                <span class="change-image positive"></span>
                <?= __('High'); ?>
            </th>
            <th class="text-align-right">
                <span class="change-image negative"></span>
                <?= __('Low'); ?>
            </th>
            <th class="text-align-right"><?= __('Gain/Loss') ?></th>
            <th class="text-center"><?= __('Watchlist'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($trader as $val): ?>
            <tr class="trader" id="trader-<?= $val['id']; ?>">
                <td class="text-align-right">
                    <?= $this->Html->link($val['from_currency_code'] . '/' . $val['to_currency_code'], [
                        '_name' => 'forex_currency',
                        'currency' => $val['from_currency_code'] . '-' . $val['to_currency_code']
                    ], [
                        'tabindex' => '-1',
                        'class' => 'link_forex',
                        'escape' => false
                    ]);?>
                </td>
                <td class="text-align-right">
                    <a href="javascript:;" class="default" data-toggle="tooltip" title="<?= $val['exchange_rate']; ?>">
                        <?= substr($this->Number->currency('', $val['to_currency_code'], []), 0, -4); ?>
                        <span class="exchange_rate">
                            <?= number_format($val['exchange_rate'], 5); ?>
                        </span>
                    </a>
                </td>
                <td class="text-align-right">
                    <a href="javascript:;" class="default" data-toggle="tooltip" title="<?= $val['high']; ?>">
                        <span class="positive"><?= substr($this->Number->currency('', $val['to_currency_code'], []), 0, -4) ?></span>
                        <span class="high_exchange_rate positive">
                            <?= number_format($val['high'], 5); ?>
                        </span>
                    </a>
                </td>
                <td class="text-align-right">
                    <a href="javascript:;" class="default" data-toggle="tooltip" title="<?= $val['low']; ?>">
                        <span class="negative"><?= substr($this->Number->currency('', $val['to_currency_code'], []), 0, -4) ?></span>
                        <span class="low_exchange_rate negative">
                            <?= number_format($val['low'], 5); ?>
                        </span>
                    </a>
                </td>
                <td class="text-align-right">
                    <span class="metric-change">
                        <span class="change-image <?= (($val['exchange_rate'] - $val['high'] >= 0) ? 'positive' : 'negative'); ?>"></span>

                        <a href="javascript:;" class="default" data-toggle="tooltip" title="<?= $val['exchange_rate'] - $val['high']; ?>">
                            <span class="change font-size <?= (($val['exchange_rate'] - $val['high'] >= 0) ? 'positive' : 'negative'); ?>">
                                <?php if ($val['exchange_rate'] - $val['high'] > 0 || ($val['exchange_rate'] - $val['high']) * (-1) > 0): ?>
                                    <?= substr($this->Number->currency('', $val['to_currency_code'], []), 0, -4) . ' ', number_format($val['exchange_rate'] - $val['high'], 5); ?>
                                <?php else: ?>
                                    <?= substr($this->Number->currency('', $val['to_currency_code'], []), 0, -4) . ' ', number_format($val['exchange_rate'] - $val['high'], 5); ?>
                                <?php endif; ?>
                                <?= ' (' . $this->Number->toPercentage(($val['exchange_rate'] - $val['high']) * 100 / $val['exchange_rate']) . ')'; ?>
                            </span>
                        </a>

                    </span>
                </td>
                <td class="text-center">
                    <?php $hasItem = false; ?>
                    <?php foreach($watchlistItems as $item): ?>
                        <?php if ($item->trader_id == Hash::get($val, 'id')): ?>
                            <?php $hasItem = true; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($hasItem): ?>
                        <?= $this->Form->postLink($this->Html->tag('i', '', ['class' => 'fa fa-remove']), [
                            'controller' => 'watchlist_forex',
                            'action' => 'removeItem',
                            Hash::get($val, 'id')
                        ], [
                            'escape' => false,
                            'confirm' => 'Are you sure to remove this item?',
                            'class' => 'users'
                        ]); ?>
                    <?php else: ?>
                        <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-eye']), 'javascript:;', [
                            'escape' => false,
                            'class' => 'js-watchlist',
                            'data-url' => $this->Url->build(['controller' => 'watchlist_forex', 'action' => 'addItem', Hash::get($val, 'id')])
                        ]); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->start('script'); ?>
    <?= $this->Html->script('watchlist/watchlist_bond_forex.js'); ?>
    <script type="text/javascript">
        $(function() {
            new Watchlist();
        });
    </script>
<?php $this->end();?>

<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Watchlist Forex</h4>
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