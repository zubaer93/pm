<?php $this->Html->script(['portfolio/transaction.js'], ['block' => 'script']);?>
<?php $this->Html->script(['portfolio/calculator.js'], ['block' => 'script']);?>

<?= $this->Html->script('https://code.jquery.com/jquery-2.2.3.min.js'); ?>
<?= $this->Html->script('FrontendTheme.../js/chart/raphael-min'); ?>
<?= $this->Html->script('FrontendTheme.../js/chart/morris.min'); ?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-0">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <h2 class="text-center mt-15">
            <?= __('Trade Summary'); ?>
        </h2>
    </div>
    <!--    <div class="col-lg-2 col-md-2 col-sm-6"></div>-->
    <div class="col-lg-2 col-md-2 col-sm-6">
        <div class="mt-p-3 mt-20 float-right">
            <?= $this->element('Links/quick_links'); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-1 col-sm-12 col-md-1"></div>
    <div class="col-lg-4 col-sm-12 col-md-4">
        <div class="box-inner">
            <ul class="nav nav-tabs nav-top-border" role="tablist">
                <a class="active" href="#order_form" data-toggle="tab" role="tablist"><?= __('Order Form') ?></a>
                <a href="#forex" data-toggle="tab" role="tablist"><?= __('Forex') ?></a>
            </ul>
            <div class="tab-content mt-20">
                <?= $this->element('TradeSummary/tabOrderForex'); ?>
            </div>
        </div>

    </div>
    <div class="col-lg-6 col-sm-12 col-md-6 transaction-data" data-cancel-url ="<?= $this->Url->build(['_name' => 'transaction_cancel']); ?>">
             <?php foreach ($all_transactions as $data): ?>
            <div class="box-light mb-5 cancel-transaction-<?= $data->id ?>">
                <div class="box-inner">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-6">
                            <?php
                                $paramChart = \App\Model\Service\TranscactionChart::transactionChart($data->company->symbol, $data->company_id, $data->created_at, $currentLanguage);
                                $paramChart = json_decode($paramChart, true);
                                $min = $paramChart['min'];
                                $min = min($min);
                                $min = floor($min);
                                unset($paramChart['min']);
                            ?>
                            <p class="mt-12 mb-14 fs-25">
                                <?= __('Trade Summary'); ?>
                            <d class="font-lato"></d>
                            </p>
                            <p class="mb-0 fs-18">
                                <?= __('Market: '); ?>
                                <b class="font-lato"><?= $data->market; ?></b>
                            </p>
                            <p class="fs-18">
                                <?= __('Quantity: '); ?>
                                <b  class="font-lato"><?= $data->quantity_to_buy; ?></b>
                            </p>
                            <p class="mb-0 fs-18">
                                <?= __('Company Price: '); ?>
                                <b class="font-lato"><?= $this->Number->currency($data->price, 'USD'); ?></b>
                            </p>
                            <?php if (!is_null($data->limit_price)): ?>
                                <p class="mb-0 fs-18">
                                    <?= __('Limit Price: '); ?>
                                    <b class="font-lato"><?= $this->Number->currency($data->limit_price, 'USD'); ?></b>
                                </p>
                            <?php endif; ?>
                            <p class="fs-18">
                                <?= __('Action: '); ?>
                                <b class="font-lato">
                                    <?php
                                    foreach ($action as $key => $val):
                                        if ($key === $data->action) {
                                            echo $val;
                                        }
                                    endforeach;
                                    ?>
                                </b>
                            </p>
                            <p class="fs-25">
                                <?= __('Total Cost: '); ?>
                                <b class="font-lato"><?= $this->Number->currency($data->total, 'USD'); ?></b>
                            </p>
                        </div>

                        <div class="col-sm-12 col-lg-6 col-md-6">
                            <div class="col-sm-12 col-lg-12 col-md-12">
                                <p class="mt-80 mb-0 fs-18">
                                    <?= __('Inv type: '); ?>
                                    <b class="font-lato">
                                        <?php
                                        foreach ($inv_type as $key => $val):
                                            if ($key === $data->investment_preferences) {
                                                echo $val;
                                            }
                                        endforeach;
                                        ?>
                                    </b>
                                </p>
                                <p class="fs-18">
                                    <?= __('Order Type: '); ?>
                                    <b  class="font-lato">
                                        <?php
                                        foreach ($orderType as $key => $val):
                                            if ($key === $data->order_type) {
                                                echo $val;
                                            }
                                        endforeach;
                                        ?>
                                    </b>
                                </p>
                                <p class="mb-0 fs-18">
                                    <?= __('Time: '); ?>
                                    <b class="font-lato fs-14"><?= $data->created_at->nice(); ?></b>
                                </p>
                                <p class="mb-0 fs-18">
                                    <?= __('Fees: '); ?>
                                    <b class="font-lato"><?= $this->Number->currency($data->fees, 'USD'); ?></b>
                                </p>
                                <p class=" mb-0 fs-18">
                                    <?= __('Company: '); ?>
                                    <b class="font-lato"><?= $data->company->symbol; ?></b>
                                </p>

                                <p class="mt-5">
                                    <?= $this->Html->link(__('Place Order'), ['_name' => 'place-order', $data->id], ['class' => 'btn btn-danger']); ?>
                                </p>
                            </div>
                        </div>
                        <?php if (!empty($paramChart)): ?>
                        <!-- GRAPH CONTAINER -->
                            <?= $this->element('Chart/transaction_chart', ['paramChart' => json_encode($paramChart), 'id' => $data->id, 'symbol' => $data->company->symbol, 'min' => $min]); ?>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-6"></div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="row pull-right">
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <?=
                                    $this->Html->link(__('Edit'), ['_name' => 'portfolio_edit', $data->id], [
                                        'tabindex' => '-1',
                                        'escape' => false,
                                        'class' => 'btn btn-primary'
                                            ]
                                    );
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6">
                                    <?=
                                    $this->Html->link(__('Cancel'), [null], [
                                        'tabindex' => '-1',
                                        'data-id' => $data->id,
                                        'data-toggle' => 'modal',
                                        'escape' => false,
                                        'class' => 'btn btn-danger cancel_transaction'
                                            ]
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row">
            <div class="col-md-9 col-sm-12 col-lg-6" ></div>
            <div class="col-md-3 col-sm-12 col-lg-6">
                <ul class="pagination justify-content-center pull-right">
                    <?= $this->Paginator->prev('« ' . __('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next') . ' »') ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-sm-12 col-md-1"></div>
</div>

<div class="modal" id="confirm-cancel-transaction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Are you sure you want to cancel?'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('NO'); ?></button>
                <a class="btn btn-danger btn-ok cancle-transaction tex-white" data-id=""><?= __('YES'); ?></a>
            </div>
        </div>
    </div>
</div>
<div data-notify="container" id="alert-success" class="col-sm-2 col-lg-2 col-md-2 alert btn-primary alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>
