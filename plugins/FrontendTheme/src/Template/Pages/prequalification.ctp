<?php

use Cake\Core\Configure;
?>

<!-- -->
<section>
    <div class="container">

        <!-- ALERT -->
        <?= $this->Flash->render() ?>
        <!-- /ALERT -->
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->create(null, ['class' => 'm-0 sky-form boxed']); ?>
                <header>
                    <i class="fa fa-users"></i> <?= __('Market Prequalification system') ?>
                </header>
                <fieldset class="m-0">
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <label class="input">
                                <?= $this->Form->control('first_name', ['required' => true, 'label' => false, 'placeholder' => 'First name']); ?>
                            </label>
                        </div>
                        <div class="col col-md-6">
                            <label class="input">
                                <?= $this->Form->control('last_name', ['required' => true, 'label' => false, 'placeholder' => 'Last name']); ?>
                            </label>
                        </div>
                    </div>
                    <label class="input mb-10">
                        <?= $this->Form->control('age', ['required' => true, 'min' => 18, 'max' => 120, 'type' => 'number', 'label' => false, 'placeholder' => 'Age']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Needed to verify your account') ?></b>
                    </label>
                    <label class="select mb-10 mt-20">
                        <i class=""></i>
                        <?= $this->Form->select('market_to_trade', \App\Model\Service\Core::$market_to_trade, ['empty' => 'Market you would like to Trade on', 'class' => 'market_to_trade', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Market you would like to Trade on') ?></b>
                    </label>
                    <label class="select mb-10 mt-20">
                        <i class=""></i>
                        <?= $this->Form->select('country_currently', \App\Model\Service\Core::$countryCurrently, ['empty' => 'Country currently living in', 'class' => 'country_currently', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Country currently living in') ?></b>
                    </label>
                    <label class="select mb-10 mt-20 us_residency" style="display: none;">
                        <i class=""></i>
                        <?= $this->Form->select('us_residency', \App\Model\Service\Core::$us_residencyin, ['empty' => 'US Residencyin']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('US Residencyin') ?></b>
                    </label>
                    <div class="mt-20">
                        <label class="checkbox m-0">

                            <?= $this->Form->checkbox('passport', ['class' => 'checked-passport', 'label' => false, 'checked' => true]); ?>
                            <i></i><?= __('Do you have a Passport ?') ?>
                        </label>
                    </div>
                    <label class="input mb-10 mt-20 passport_exp" >
                        <i class="ico-append fa fa-calendar"></i>
                        <?= $this->Form->control('passport_exp', ['id' => 'passport_exp', 'type' => 'text', 'required' => true, 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => __('Passport Exp')]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Date of Passport Exp') ?></b>
                    </label>
                    <div class="mt-30 mb-10">
                        <label class="checkbox m-0">

                            <?= $this->Form->checkbox('visa', ['class' => 'checked-visa', 'label' => false, 'checked' => true]); ?>
                            <i></i><?= __('Do you have a US Visa ?') ?>
                        </label>
                    </div>
                    <label class="input mb-10 mt-20 visa_exp_date">
                        <i class="ico-append fa fa-calendar"></i>
                        <?= $this->Form->control('visa_exp_date', ['type' => 'text', 'required' => true, 'class' => 'form-control datepicker', 'id' => 'visa_exp_date', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => __('Visa Exp date')]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Visa Exp date') ?></b>
                    </label>
                </fieldset>

                <div class="row mb-20">
                    <div class="col-md-12">
                        <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i>' . __('Check')), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>

<!-- MODAL -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModal"><?= __('Market Prequalification ') ?></h4>
            </div>

            <div class="modal-body modal-short">
                <?php if ($bool): ?>
                    <?= __('Congratulations ') . $first_name ?>
                    <p>
                        <?= __('You are able to trade on the following markets:') ?>
                    </p>
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <?=
                            $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' US Market'), '/USD/dashboard', [
                                'tabindex' => '-1',
                                'class' => 'color_blue',
                                'escape' => false
                                    ]
                            );
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' Jam Market'), '/JMD/dashboard', [
                                'tabindex' => '-1',
                                'class' => 'color_red',
                                'escape' => false
                                    ]
                            );
                            ?>

                        </div>
                    </div>
                    <p><?= __('Here are some suggested Brokers'); ?></p>
                    <ul>
                        <?php foreach (\App\Model\Service\Core::$brokers as $broker): ?>
                            <li>  
                                <?= __($broker); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p><?= __('Go to Brokers to view more details and comparison'); ?></p>
                    <p><?= __('Required Documents:'); ?></p>
                    <ol>
                        <?php foreach (\App\Model\Service\Core::$required_documents as $document): ?>
                            <li>  
                                <?= __($document); ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <?= __('Was rejected because you did not correctly fill out your details')?>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php $this->Html->scriptStart(['block' => true]); ?>
/**
Checkbox on "Visa"
**/
var USA = '<?= \App\Model\Service\Core::$countryCurrently[3]; ?>';
var Barbados = '<?= \App\Model\Service\Core::$countryCurrently[2]; ?>';
var Jamaica = '<?= \App\Model\Service\Core::$countryCurrently[1]; ?>';
var checked = '<?= $checked; ?>';
var bool = '<?= $bool; ?>';

$(function(){
    if(checked == 1){
        $('#termsModal').modal('toggle');
    }
});


$(".checked-visa").click(function () {

        if ($(this).is(':checked')) {
        $("#visa_exp_date").prop('required',true);
        $('.visa_exp_date').show();
        }else{
        $("#visa_exp_date").prop('required',false);
        $('.visa_exp_date').hide();
        }
        });
        $(".checked-passport").click(function () {

        if ($(this).is(':checked')) {
        $("#passport_exp").prop('required',true);
        $('.passport_exp').show();
        }else{
        $("#passport_exp").prop('required',false);
        $('.passport_exp').hide();
        }
        });
        $(".country_currently").change(function () {

        if ($(".country_currently option:selected" ).text() == USA) {
        $('.us_residency').show();
        }else{
        $('.us_residency').hide();
        }

});

<?php $this->Html->scriptEnd(); ?>