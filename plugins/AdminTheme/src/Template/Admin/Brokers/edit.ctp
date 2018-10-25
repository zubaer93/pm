<section id="middle">

    <header id="page-header">
        <h1><?= __('Edit Broker'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Broker'), ['_name' => 'brokers_list']); ?></li>
            <li class="active"><?= __('Edit Broker'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit Broker'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('news', []); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id', ['value' => $broker->id]); ?>

                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Name *'); ?></label>
                                <?= $this->Form->control('first_name', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $broker->first_name, 'value' => $broker->first_name]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Market *'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('market', \App\Model\Service\Core::$market, ['required' => true, 'default' => $broker->market, 'empty' => 'Select Market', 'class' => 'form-control', 'required' => true]); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Fee *'); ?></label>
                                <?= $this->Form->control('fee', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $broker->fee, 'value' => $broker->fee]); ?>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Exchange Fee *'); ?></label>
                                <?= $this->Form->control('exchange_fee', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $broker->exchange_fee, 'value' => $broker->exchange_fee]); ?>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Trade Fee *'); ?></label>
                                <?= $this->Form->control('trade_fee', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $broker->trade_fee, 'value' => $broker->trade_fee]); ?>
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
