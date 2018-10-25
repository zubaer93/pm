<section id="middle">

    <header id="page-header">
        <h1><?= __('Add Partner'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Partner'), ['_name' => 'partners_list']); ?></li>
            <li class="active"><?= __('Add Partner'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Add Partner'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('news',['enctype' => 'multipart/form-data']); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Name *'); ?></label>
                                <?= $this->Form->control('name', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => __('Name')]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('SubDomain *'); ?></label>
                                <?= $this->Form->control('subdomain', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => __('SubDomain')]); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <?= __('File Attachment '); ?>
                                </label>

                                <!-- custom file upload -->
                                <div class="fancy-file-upload fancy-file-primary">
                                    <i class="fa fa-upload"></i>
                                    <input type="file" class="form-control valid" name="image" onchange="jQuery(this).next('input').val(this.value);">
                                    <input type="text" class="form-control" placeholder="no image selected" readonly="">
                                    <span class="button"> <?= __('Choose Image'); ?></span>
                                </div>
                                <small class="text-muted block"><?= __('Max file size: 2Mb (jpg/png) '); ?></small>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Main Color *'); ?></label>
                                <?=
                                $this->Form->control('main_color', ['required' => true,
                                    'type' => 'text',
                                    'style' => "display: none;",
                                    'class' => 'form-control colorpicker',
                                    'data-fullpicker' => 'true',
                                    'data-defaultcolor' => '#8ab933',
                                    'label' => false,
                                    'placeholder' => __('Main Color')]);
                                ?>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Main Border Color *'); ?></label>
                                <?=
                                $this->Form->control('main_border_color', ['required' => true,
                                    'type' => 'text',
                                    'style' => "display: none;",
                                    'class' => 'form-control colorpicker',
                                    'data-fullpicker' => 'true',
                                    'data-defaultcolor' => '#8ab933',
                                    'label' => false,
                                    'placeholder' => __('Main Border Color')]);
                                ?>
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
