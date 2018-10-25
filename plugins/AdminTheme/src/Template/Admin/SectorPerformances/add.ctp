<section id="middle">

    <header id="page-header">
        <h1><?= __('Add Sector Performance'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Sector Performances'), ['_name' => 'sector_performances']); ?></li>
            <li class="active"><?= __('Add Sector Performance'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Add Sector Performance'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('news', []); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Name *'); ?></label>
                                <?= $this->Form->control('name', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Percent *'); ?></label>
                                <?= $this->Form->control('percent', ['required' => true, 'type' => 'number', 'class' => 'form-control', 'label' => false]); ?>

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
