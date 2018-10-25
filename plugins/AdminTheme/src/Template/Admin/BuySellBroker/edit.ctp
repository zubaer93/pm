<section id="middle">
    <?php $action_admin = App\Model\Service\Core::$action_admin; ?>
    <div class="partial" style="display: none">
        <div class="col-md-12 col-sm-12 broker_company">
            <div class="row">
                <br>
                <div class="col-md-12 col-sm-12">
                    <a type="button" class="btn btn-3d btn-danger btn-xs delete_main_partial">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <label><?= __('Brokers'); ?></label>
                    <i class=""></i>
                    <?= $this->Form->select('broker_id[]', $all_brokers, ['required' => true, 'class' => 'form-control new_select',]); ?>                  
                </div>
                <div class="col-md-4 col-sm-4">
                    <label><?= __('Date'); ?> </label>
                    <?= $this->Form->control('broker_created_at[]', ['required' => true, 'value' => (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format('Y-m-d'), 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => '' . date('Y-m-d') . '']); ?>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label><?= __('Status'); ?></label>
                    <i class=""></i>
                    <?= $this->Form->select('status[]', $action_admin, ['required' => true, 'class' => 'form-control new_select',]); ?>                  
                </div>
            </div>
        </div>
    </div>
    <header id="page-header">
        <h1><?= __('Edit Sell Buy'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Sell Buy'), ['_name' => 'buy_sell_broker']); ?></li>
            <li class="active"><?= __('Edit Sell Buy'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit Sell Buy'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('sell_buy'); ?>

                    <?= $this->Form->hidden('id', ['value' => $broker->id]); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Company Symbol'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('company_id', $all_companies, ['default' => $broker->company_id, 'id' => 'dates-field2', 'class' => 'form-control select2',]); ?>                  
                            </div>    
                            <?php foreach ($broker_details as $details): ?>
                                <div class="col-md-12 col-sm-12  broker_company">
                                    <div class="row">
                                        <br>
                                        <div class="col-md-12 col-sm-12">
                                            <a type="button" class="btn btn-3d btn-danger btn-xs delete_main_partial"><i class="fa fa-times"></i></a>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <label><?= __('Brokers'); ?></label>
                                            <i class=""></i>
                                            <?= $this->Form->select('broker_id[]', $all_brokers, ['default' => $details->broker_id, 'class' => 'form-control select2',]); ?>                  
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <label><?= __('Date'); ?> </label>
                                            <?= $this->Form->control('broker_created_at[]', ['required' => true, 'value' => $details->created_at->format('Y-m-d'), 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => '' . date('Y-m-d') . '']); ?>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <label><?= __('Status'); ?></label>
                                            <i class=""></i>
                                            <?= $this->Form->select('status[]', $action_admin, ['default' => $details->status, 'class' => 'form-control select2',]); ?>                  
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="col-md-12 col-sm-12 mt-20 add_button">
                                <a type="button" class="btn btn-3d btn-leaf pull-right add_input_file">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <?= $this->Form->button('<i class="fa fa-check"></i>' . __('SAVE'), ['class' => 'btn btn-primary btn-lg btn-block']); ?>
                            </div>
                        </div>
                    </fieldset>
                    <?= $this->Form->end(); ?>
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('#content').delegate('.delete_main_partial', 'click', function () {
            $(this).parents('.broker_company').remove();
        });
        $('#content').delegate('.add_input_file', 'click', function () {
            $($('.partial').html()).insertBefore(".add_button");
            $('.card-block').find('.new_select').addClass('select2');
            $('.card-block').find('.select2').removeClass('.new_select');
            _select2();
        });

    });
</script>