<div class="tab-pane active" id="order_form">
    <div class=" potfolio_data"
         data-currency-list="<?= $this->Url->build(['_name' => 'portfolio_get_currency_list']); ?>"
         data-currency-rate="<?= $this->Url->build(['_name' => 'portfolio_get_currency_rate']); ?>"
         data-company="<?= $this->Url->build(['_name' => 'portfolio_get_company']); ?>"
         data-broker-url="<?= $this->Url->build(['_name' => 'portfolio_get_broker_list']); ?>"
         data-company-price="<?= $this->Url->build(['_name' => 'portfolio_get_company_price']); ?>"
         data-broker-fee="<?= $this->Url->build(['_name' => 'portfolio_get_broker_fee']); ?>"
         >
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="m-0">
                    <fieldset class="m-0">
                        <?= $this->Form->create(null, ['url' => ['action' => 'savePreview'], 'type' => 'post', 'class' => 'm-0 sky-form']); ?>
                        <?= $this->Form->input('total_fee', ['type' => 'hidden', 'class' => 'total-fee']); ?>
                        <?= $this->Form->input('company_price', ['type' => 'hidden', 'class' => 'company-price']); ?>
                        <div class="box-light">
                            <div class="box-inner">
                                <div class="box-messages">
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
                                    <label class="select mt-10">
                                        <i class=""></i>
                                        <?= $this->Form->select('investment_preferences', \App\Model\Service\Core::$investmentPreferences, ['empty' => 'Investment preferences', 'class' => 'investment_preferences', 'required' => true]); ?>
                                        <b class="tooltip tooltip-bottom-right"><?= __('Investment preferences') ?></b>
                                    </label>

                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <label class="select">
                                                <?= __('Select Market') ?>
                                                <?= $this->Form->select('select_market', \App\Model\Service\Core::$market, ['empty' => '', 'default' => (isset($simulation) ? $simulation['market'] : $currentLanguage), 'class' => 'select_market', 'required' => true]); ?>
                                                <b class="tooltip tooltip-bottom-right"><?= __('Select Market') ?></b>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="select">
                                                <?= __('Company *'); ?>
                                                <select name="company" class="form-control company js-company-data-ajax select2" required></select>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <label class="select">
                                                <?= __('Order Type') ?>
                                                <?= $this->Form->select('order_type', \App\Model\Service\Core::$orderType, ['empty' => '', 'class' => 'form-control order-type', 'required' => true]); ?>
                                                <b class="tooltip tooltip-bottom-right"><?= __('Order Type') ?></b>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="select">
                                                <?= __('Action') ?>
                                                <?= $this->Form->select('action', \App\Model\Service\Core::$action, ['empty' => '', 'class' => 'form-control action', 'required' => true]); ?>
                                                <b class="tooltip tooltip-bottom-right"><?= __('Action') ?></b>
                                            </label>
                                        </div>
                                    </div>
                                    <label class="input enter-price hide">
                                        <?= __('Limit Price'); ?>
                                        <?= $this->Form->control('limit_price', ['required' => true, 'type' => 'number', 'class' => 'limit-price', 'step' => '0.01', 'min' => 0, 'label' => false, 'placeholder' => 'Limit Price', 'value' => '']); ?>
                                    </label>
                                    <div class="row mb-0">
                                        <div class="col-md-6">
                                            <label class="input">
                                                <?= __('Quantity to buy'); ?>
                                                <?= $this->Form->control('quantity_to_buy', ['required' => true, 'min' => '1', 'label' => false, 'type' => 'number', 'class' => 'form-control quantity_to_buy', 'placeholder' => 'Enter Quantity to buy', 'value' => (isset($simulation) ? $simulation['quantity'] : 100)]); ?>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="select">
                                                <?= __('Brokers *'); ?>
                                                <?= $this->Form->select('broker', $all_brokers, ['empty' => 'Select Broker', 'class' => 'broker form-control select2 js-broker-data-ajax', 'required' => true]); ?>
                                                <b class="tooltip tooltip-bottom-right"><?= __('Broker') ?></b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row glance"  style="background-color: #f2f2f2;font-size: 17px;">
                                        <div class="col-sm-12 col-lg-2 col-md-2"></div>

                                        <div class="col-md-8 col-lg-8 col-sm-10 pt-10 pb-10">
                                            <label class="input">
                                                <?= $this->Form->control('total', ['required' => true, 'label' => false, 'type' => "number", 'min' => "0", 'step' => 0.01, 'placeholder' => 'Total', 'class' => 'total', 'style' => 'border-color: #00000085', 'readonly' => 'true',]); ?>
                                            </label>
                                        </div>
                                        <div class="col-sm-12 col-lg-2 col-md-2"></div>
                                    </div>
                                    <?= $this->Form->button('Preview', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
                                </div>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane " id="forex">

    <div class="box-light">
        <div class="box-inner">
            <div class="box-messages">
                <?= $this->element('Profit/profit'); ?>
            </div>
        </div>
    </div>
</div>
