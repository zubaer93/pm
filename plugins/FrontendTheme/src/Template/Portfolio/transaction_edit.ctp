<?php
$this->Html->script(
    [
        'portfolio/calculator'
    ], ['block' => 'script']
);
?>
<div class="container potfolio_data  "

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
                    <?= __('Edit '.$data->company->name); ?>
                </h2>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="mt-30 fs-18 pull-right">
                    <?=
                    $this->Html->link( __('New'), ['_name' => 'transaction'], [
                            'tabindex' => '-1',
                            'escape' => false,
                            'class' => 'btn-link mr-20'
                        ]
                    );
                    ?>
                    <?= $this->element('Links/quick_links'); ?>
                </div>
            </div>
        </div>
    <!-- ALERT -->
    <?= $this->Flash->render() ?>
    <!-- /ALERT -->
    <fieldset class="mb-20">
        <div class="box-light">
            <div class="box-inner">
        <?= $this->Form->create(null, ['_name' => 'portfolio_edit', 'type' => 'post' ,'class' => 'm-0 sky-form']); ?>
        <?=$this->Form->input('total_fee', ['type' => 'hidden','class' => 'total-fee','value'=>$data->fees]);?>
        <?=$this->Form->input('company_price', ['type' => 'hidden','class' => 'company-price','value'=>$data->price]);?>
        <div class="row mb-10">
            <div class="col-md-6">
                <label class="input">
                    <i class="fa fa-info"></i>
                    <?= __('Client Name'); ?>
                    <?= $this->Form->control('client_name', ['required' => true, 'label' => false, 'placeholder' => 'Client name', 'value'=>$data->client_name]); ?>
                </label>
            </div>
            <div class="col-md-6">
                <label class="input">
                    <i class="fa fa-usd"></i>
                    <?= __('Investment amount'); ?>
                    <?= $this->Form->control('investment_amount', ['required' => true, 'min' => '0', 'label' => false, 'type' => 'number', 'class' => 'form-control', 'placeholder' => 'Investment amount','value' => $data->investment_amount]); ?>
                </label>
            </div>
        </div>
        <div class="row md-10">
            <div class="col-md-6">
                <i class=""></i>
                <?= $this->Form->select('order_type', \App\Model\Service\Core::$orderType, ['empty' => 'Order Type', 'default'=>$data->order_type, 'class' => 'form-control order-type', 'required' => true]); ?>
                <b class="tooltip tooltip-bottom-right"><?= __('Order Type') ?></b>
            </div>
            <div class="col-md-6">
                <i class=""></i>
                <?= $this->Form->select('action', \App\Model\Service\Core::$action, ['empty' => 'Action', 'default'=>$data->action, 'class' => 'form-control action', 'required' => true]); ?>
                <b class="tooltip tooltip-bottom-right"><?= __('Action') ?></b>
            </div>
        </div>
        <label class="input enter-price hide">
            <?= __('Limit Price'); ?>
            <?= $this->Form->control('limit_price', ['required' => true,'type'=>'number', 'class'=>'limit-price', 'step'=>'0.01', 'min'=>0, 'label' => false, 'placeholder' => 'Limit Price', 'value'=>$data->limit_price]); ?>
        </label>
        <label class="select mt-10">
            <i class=""></i>
            <?= $this->Form->select('investment_preferences', \App\Model\Service\Core::$investmentPreferences, ['empty' => 'Investment preferences', 'default'=>$data->investment_preferences, 'class' => 'investment_preferences', 'required' => true]); ?>
            <b class="tooltip tooltip-bottom-right"><?= __('Investment preferences') ?></b>
        </label>
        <label class="select mt-20 mb-10">
            <i class=""></i>
            <?= $this->Form->select('select_market', \App\Model\Service\Core::$market, ['empty' => 'Select Market', 'default' => $data->market, 'class' => 'select_market', 'required' => true]); ?>
            <b class="tooltip tooltip-bottom-right"><?= __('Select Market') ?></b>
        </label>
            </div>
        </div>
        <div class="box-light">
            <div class="box-inner">
                <div class="box-messages">
                    <h1><i class="fa fa-line-chart"></i> <?= __('Investment Glance'); ?></h1>
                    <div class="row glance ">
                        <div class="col-md-12">
                            <label class="select">
                                <?= __('Company *'); ?>
                                <select name="company" class="form-control company js-company-data-ajax select2">
                                    <option value="<?= $data->company_id; ?>" selected="selected"><?= $data->company->name; ?></option>
                                </select>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="input">
                                <?= __('Quantity to buy'); ?>
                                <?= $this->Form->control('quantity_to_buy', ['required' => true, 'min' => '1', 'label' => false, 'type' => 'number', 'class' => 'form-control quantity_to_buy', 'placeholder' => 'Enter Quantity to buy', 'value' =>  $data->quantity_to_buy]); ?>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="select">
                                <?= __('Brokers *'); ?>
                                <select name="broker" class="form-control broker js-broker-data-ajax  select2">
                                    <option value="<?= $data->broker; ?>" selected="selected"><?= $data->brokers_list->first_name; ?></option>
                                </select>
                                <b class="tooltip tooltip-bottom-right"><?= __('Broker') ?></b>
                            </label>
                        </div>
                        <div class="col-md-12 pt-10 pb-10" style="
                                             background-color: #f2f2f2;font-size: 17px;
                                             ">
                            <label class="input">
                                <?= __('Total'); ?>
                                <?= $this->Form->control('total', ['required' => true, 'label' => false, 'type' => "number", 'min' => "0",'value'=>$data->total, 'step' => 0.01, 'placeholder' => 'Total', 'class' => 'total',  'readonly' => 'true',]); ?>
                            </label>
                        </div>
                    </div>
                    <?php
                    if(isset($authUser)):
                        echo $this->Form->button('Update', ['type' => 'submit', 'class' => 'btn btn-primary mt-15']);
                    endif
                    ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </fieldset>
</div>
