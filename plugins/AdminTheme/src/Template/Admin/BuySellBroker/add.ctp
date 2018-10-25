<section id="middle">

    <header id="page-header">
        <h1><?= __('Add Sell Buy'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Sell Buy'), ['_name' => 'buy_sell_broker']); ?></li>
            <li class="active"><?= __('Add Sell Buy'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Add Sell Buy'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('sell_buy'); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Company Symbol'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('company_id', $all_companies, ['id' => 'dates-field2', 'class' => 'form-control select2',]); ?>                  
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Brokers'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('broker_id', $all_brokers, ['id' => 'dates-field2', 'class' => 'form-control select2',]); ?>                  
                            </div>
                            <div class="col-md-6 col-sm-4">
                                <label><?= __('Status'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('status', App\Model\Service\Core::$action_admin, ['id' => 'dates-field2', 'class' => 'form-control select2',]); ?>                  

                            </div>
                            <div class="col-md-6 col-sm-4">
                                <label><?= __('Date'); ?> </label>
                                <?= $this->Form->control('created_at', ['required' => true,'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'label' => false, 'placeholder' => '' . date('Y-m-d H:i:s') . '']); ?>
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
