 <?php
$this->Html->script(
        [
    'portfolio/calculator'
        ], ['block' => 'script']
);
?>

<div class="container potfolio_data"
     data-currency-list="<?= $this->Url->build(['_name' => 'portfolio_get_currency_list']); ?>"
     data-currency-rate="<?= $this->Url->build(['_name' => 'portfolio_get_currency_rate']); ?>"
     data-company="<?= $this->Url->build(['_name' => 'portfolio_get_company']); ?>"
     data-broker-url="<?= $this->Url->build(['_name' => 'portfolio_get_broker_list']); ?>"
     data-company-price="<?= $this->Url->build(['_name' => 'portfolio_get_company_price']); ?>"
     data-broker-fee="<?= $this->Url->build(['_name' => 'portfolio_get_broker_fee']); ?>"
     >

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <h2 class="text-center mt-15">
                <?= __('Order Form') ?>
            </h2>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="mt-30 fs-18 pull-right">
                <?=
                $this->Html->link(__('Order History'), ['_name' => 'transaction'], [
                    'tabindex' => '-1',
                    'escape' => false,
                    'class' => 'btn-link'
                        ]
                );
                ?>
            </div>
        </div>
    </div>

    <!-- ALERT -->
    <?= $this->Flash->render() ?>
    <!-- /ALERT -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="m-0 sky-form boxed">
                <fieldset class="m-0">
                    <?= $this->Form->create(null, ['url' => ['action' => 'savePreview'], 'type' => 'post', 'class' => 'm-0 sky-form']); ?>
                    <?= $this->Form->input('total_fee', ['type' => 'hidden', 'class' => 'total-fee']); ?>
                    <?= $this->Form->input('company_price', ['type' => 'hidden', 'class' => 'company-price']); ?>
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <label class="input">
                                <i class="fa fa-info"></i>
                                <?= __('Client Name'); ?>
                                <?= $this->Form->control('client_name', ['required' => true, 'label' => false, 'placeholder' => 'Client name', 'value' => $client_name]); ?>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="input">
                                <i class="fa fa-usd"></i>
                                <?= __('Investment amount'); ?>
                                <?= $this->Form->control('investment_amount', ['required' => true, 'min' => '0', 'label' => false, 'type' => 'number', 'class' => 'form-control', 'placeholder' => 'Investment amount', 'value' => (isset($simulation) ? $simulation['investment_amount'] : 100000)]); ?>
                            </label>
                        </div>
                    </div>
                    <div class="row md-10">
                        <div class="col-md-6">
                            <i class=""></i>
                            <?= $this->Form->select('order_type', \App\Model\Service\Core::$orderType, ['empty' => 'Order Type', 'class' => 'form-control order-type', 'required' => true]); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Order Type') ?></b>
                        </div>
                        <div class="col-md-6">
                            <i class=""></i>
                            <?= $this->Form->select('action', \App\Model\Service\Core::$action, ['empty' => 'Action', 'class' => 'form-control action', 'required' => true]); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Action') ?></b>
                        </div>
                    </div>
                    <label class="input enter-price hide">
                        <?= __('Limit Price'); ?>
                        <?= $this->Form->control('limit_price', ['required' => true, 'type' => 'number', 'class' => 'limit-price', 'step' => '0.01', 'min' => 0, 'label' => false, 'placeholder' => 'Limit Price', 'value' => '']); ?>
                    </label>
                    <label class="select mt-10">
                        <i class=""></i>
                        <?= $this->Form->select('investment_preferences', \App\Model\Service\Core::$investmentPreferences, ['empty' => 'Investment preferences', 'class' => 'investment_preferences', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Investment preferences') ?></b>
                    </label>
                    <label class="select mt-20 mb-10">
                        <i class=""></i>
                        <?= $this->Form->select('select_market', \App\Model\Service\Core::$market, ['empty' => 'Select Market', 'default' => (isset($simulation) ? $simulation['market'] : $currentLanguage), 'class' => 'select_market', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Select Market') ?></b>
                    </label>
                    <div class="box-light">
                        <div class="box-inner">
                            <div class="box-messages">
                                <h1><i class="fa fa-line-chart"></i> <?= __('Investment Glance'); ?></h1>
                                <div class="row glance ">
                                    <div class="col-md-12">
                                        <label class="select">
                                            <?= __('Company *'); ?>
                                            <select name="company" class="form-control company js-company-data-ajax select2" required></select>                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="input">
                                            <?= __('Quantity to buy'); ?>
                                            <?= $this->Form->control('quantity_to_buy', ['required' => true, 'min' => '1', 'value' => 1, 'label' => false, 'type' => 'number', 'class' => 'form-control quantity_to_buy', 'placeholder' => 'Enter Quantity to buy', 'value' => (isset($simulation) ? $simulation['quantity'] : 100)]); ?>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="select">
                                            <?= __('Brokers *'); ?>
                                            <select name="broker" class="form-control broker js-broker-data-ajax  select2" required></select>
                                            <b class="tooltip tooltip-bottom-right"><?= __('Broker') ?></b>
                                        </label>
                                    </div>
                                    <div class="col-md-12 pt-10 pb-10" style="
                                         background-color: #f2f2f2;font-size: 17px;
                                         ">
                                        <label class="input">
                                            <?= __('Total'); ?>
                                            <?= $this->Form->control('total', ['required' => true, 'label' => false, 'type' => "number", 'min' => "0", 'step' => 0.01, 'placeholder' => 'Total', 'class' => 'total', 'style' => 'border-color: #00000085', 'readonly' => 'true',]); ?>
                                        </label>
                                    </div>
                                </div>
                                <?= $this->Form->button('Preview', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                            </div>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                    <div class="box-light">
                        <div class="box-inner">
                            <div class="box-messages">
                                <?= $this->element('Profit/profit'); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>





