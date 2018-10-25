<?php
$this->Html->script(
        [
    'chart/simulations_chart.js'
        ], ['block' => 'script']
);
?>
<?= $this->Html->script('https://code.jquery.com/jquery-2.2.3.min.js'); ?>
<?= $this->Html->script('FrontendTheme.../js/chart/raphael-min'); ?>
<?= $this->Html->script('FrontendTheme.../js/chart/morris.min'); ?>
<div class="container mt-15">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-0">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <h3><?= __('Your Portfolio'); ?></h3>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
            <div class="mt-p-2 float-right">
                <?= $this->element('Links/quick_links'); ?>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="toggle toggle-transparent toggle-noicon pt-12">
                <div class="toggle">

                    <label class="padding-0">
                        <span><b><?= __('Inv Amount:'); ?></b></span><?= $this->Number->currency($totalInvAmount, 'USD'); ?>
                    </label>
                    <div class="toggle-content padding-0">
                        <div class="row float-right text-left mb-10">

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span><b><?= __('Gain/Loss:'); ?></b></span> <span class="<?= $totalPrice >= 0 ? 'positive' : 'negative' ?>"><?= $this->Number->currency($totalPrice, 'USD'); ?></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span><b><?= __('Account value:'); ?></b></span> <span ><?= $this->Number->currency((isset($simulation_setting->investment_amount) ? ($totalInvAmount + $totalPrice + $simulation_setting->investment_amount - $totalInvAmount) : 0), 'USD'); ?></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span><b><?= __('Available for investing :'); ?></b></span><span class="<?= $simulation_setting->investment_amount - $totalInvAmount >= 0 ? 'positive' : 'negative' ?>"><?= $this->Number->currency($simulation_setting->investment_amount - $totalInvAmount, 'USD'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row mb-10">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="switch switch_market switch-primary mb-0" style="width: 100% !important;">
                        <input type="checkbox" <?= ($currentLanguage == 'USD') ? 'checked' : ''; ?> class="switch_market_input">
                        <span class="switch-label" data-on="USD" data-off="JMD"></span>
                        <span ><b><?= __('More transactions'); ?></b></span>
                    </label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">

                    <div class="float-right">                        
                        <a style="cursor: not-allowed;" href="#" class="btn btn-danger btn-sm delete-simulation-button disabled" data-toggle = "modal" data-target = "#deleteModal"><i class="fa fa-trash white"></i><?= __('Delete'); ?> <span class="checked_count"></span></a>
                        <?=
                        $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus white']) . __('New'), ['_name' => 'transaction'], [
                            'tabindex' => '-1',
                            'escape' => false,
                            'class' => 'btn btn-primary btn-sm'
                                ]
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-vertical-middle" data-delete-url="<?= $this->Url->build(['_name' => 'delete_simulation']); ?>"  style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox">
                                    <input type="checkbox" class="checkAll" name="checkAll" value="1">
                                    <i></i> &nbsp;
                                </label>
                            </th>
                            <th><?= __('SYMBOL'); ?></th>
                            <th><?= __('Quantity'); ?></th>
                            <th><?= __('Price'); ?></th>
                            <th><?= __('Inv Amount'); ?></th>
                            <th><?= __('Gain/Loss'); ?></th>
                            <th><?= __('Date Invested'); ?></th>
                            <th><?= __('Total'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_simulation as $val): ?>
                            <?php
                            $gainLoss = \App\Controller\PortfolioController::setSimulations($val->company->symbol, $val->company->id, $currentLanguage, $val->price, $simulation_setting->quantity);
                            $inv_amount = $simulation_setting->quantity * $val->price;
                            $companyPrice = \App\Controller\PortfolioController::setCompanyPrice($val->price, $simulation_setting, $gainLoss, $inv_amount);
                            $total = $companyPrice['total'];
                            ?>
                            <tr class="simulation-ajax-<?= $val->id ?>"
                                data-stock="<?= $val->company->symbol; ?>"
                                data-compony-id = "<?= $val->company->id ?>"
                                data-time = "<?= $val->created_at ?>"
                                data-price = "<?= $val->price ?>"
                                data-gain-Loss = "<?= $gainLoss; ?>"
                                data-total = "<?= $total; ?>"
                                data-quantity = "<?= $simulation_setting->quantity ?>"
                                data-sim-url="<?= $this->Url->build(['_name' => 'get_chart_simulation']); ?>"
                                data-fees = "<?= $companyPrice['fees']; ?>"
                                data-broker = "<?= $companyPrice['broker']; ?>"
                                >
                                <td> <label class="checkbox">
                                        <input type="checkbox"  class="checkbox_analysis" value="<?= $val->id ?>">
                                        <i></i> &nbsp;
                                    </label>
                                </td>
                                <td >
                                    <a href="javascript:;" onclick="jQuery('#pre-<?= $val->id ?>').slideToggle();chart(<?= $val->id; ?>);" ><?= $val->company->symbol ?></a>
                                </td>
                                <td><?= $simulation_setting->quantity; ?></td>
                                <td class=""><?= $this->Number->currency($val->price, 'USD'); ?></td>
                                <td class=""><?= $this->Number->currency($inv_amount, 'USD'); ?></td>
                                <td class="<?= $gainLoss >= 0 ? 'positive' : 'negative' ?>"><?= $this->Number->currency($gainLoss, 'USD'); ?></td>
                                <td class=""><?= $val->created_at->nice() ?></td>
                                <td class=""><?= $this->Number->currency($total, 'USD'); ?></td>
                            </tr>
                            <tr class="simulation-<?= $val->id ?>">
                                <td colspan="16">
                                    <div  id="pre-<?= $val->id ?>" class="hide" >
                                        <div class="sim_chart-<?= $val->id; ?>">
                                            <img class="img-centre" src="/frontend_theme/img/loading-small.gif" alt="Loader" >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <ul class="pagination justify-content-center pull-right">
                    <?= $this->Paginator->prev('« ' . __('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next') . ' »') ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?= __('Trade Simulations'); ?></h4>
                </div>
                <div class="modal-body">
                    <?= __('Are you sure you want to delete?'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"  data-dismiss="modal"><?= __('Cancel'); ?></button>
                    <a class="btn btn-danger btn-ok tex-white" onclick="deleteSimulationRow()" data-dismiss="modal"><?= __('Delete'); ?></a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->Html->scriptStart(['block' => true]); ?>
_toggle();
$('.switch_market').click(function (event) {
event.preventDefault();
if ($('.switch_market_input').is(':checked')) {

window.location = "<?=
$domain . $this->Url->build([
    "_name" => "all-simulations",
    "lang" => "JMD"
]);
?>";
} else {
window.location = "<?=
$domain . $this->Url->build([
    "_name" => "all-simulations",
    "lang" => "USD"
]);
?>";
}
});
<?php $this->Html->scriptEnd(); ?>
