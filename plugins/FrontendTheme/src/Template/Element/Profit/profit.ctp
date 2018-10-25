<h1><i class="fa fa-exchange"></i> <?= __('Forex'); ?></h1>
<div class="row">
    <div class="col-md-12">
        <label class="select">
            <?= __('Account Currency'); ?>
            <?= $this->Form->select('account_currency', array_flip(\App\Model\Service\Currency::$currency), ['empty' => 'Select Account Currency', 'required' => true, 'class' => 'form-control select2 account_currency']); ?>
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="select">
            <?= __('Currency Pair'); ?>
            <?= $this->Form->select('currency_pair', [], ['empty' => 'Select Currency Pair', 'required' => true, 'class' => 'currency_pair form-control select2']); ?>
            <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="input">
            <?= __('Current Price'); ?> <span class="current_price_span"></span>
            <?= $this->Form->control('current_price', ['required' => true, 'label' => false, 'placeholder' => 'Current Price', 'class' => 'current_price form-control', 'readonly' => 'readonly']); ?>
        </label>
    </div>
    <div class="col-md-6">
        <label class="input">
            <?= __('Trade Price'); ?> <span class="trade_price_span"></span>
            <?= $this->Form->control('trade_price', ['required' => true, 'label' => false, 'placeholder' => 'Trade Price', 'class' => 'trade_price form-control']); ?>
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="input">
            <?= __('Number of Units'); ?>
            <?= $this->Form->control('number_of_units', ['required' => true, 'min' => '0', 'label' => false, 'class' => 'form-control stepper number_of_units', 'placeholder' => 'Number of Units']); ?>
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-12 pt-10 pb-10" style="
         background-color: #f2f2f2;font-size: 17px; ">
        <label class="input">
            <?= __('Profit'); ?>
            <?= $this->Form->control('profit', ['required' => true, 'label' => false, 'placeholder' => 'Profit', 'class' => 'profit form-control', 'style' => 'border-color: #00000085','readonly' => 'true']); ?>
        </label>
    </div>
</div>
