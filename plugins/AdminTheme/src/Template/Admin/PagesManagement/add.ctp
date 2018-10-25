<section id="middle">

    <header id="page-header">
        <h1><?= __('Add Page'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Page'), ['_name' => 'all_pages']); ?></li>
            <li class="active"><?= __('Edit News'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Add Page'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('page'); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Page Name *'); ?></label>
                                <?= $this->Form->control('name', ['type' => 'text','class' => 'form-control', 'label' => false, 'placeholder' => __('Page Name'), 'value' => '']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label><?= __('Body'); ?> </label>
                                <?= $this->Form->textarea('body', ['type' => 'text', 'data-height' => '200', 'data-lang' => 'en-US', 'class' => 'form-control summernote', 'placeholder' =>  __('Page Body'), 'value' => '']); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Position'); ?></label>
                                <?= $this->Form->control('position', ['type' => 'number', 'class' => 'form-control', 'label' => false, 'placeholder' => __('Page Position'), 'value' => '']); ?>
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