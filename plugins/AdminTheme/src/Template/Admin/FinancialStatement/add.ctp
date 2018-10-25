<section id="middle">
    <header id="page-header">
        <h1><?= __('Add Financial Statement'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Financial Statement'), ['_name' => 'financial_statement']); ?></li>
            <li class="active"><?= __('Add Financial Statement'); ?></li>
        </ol>
    </header>
    <div class="input_partial" hidden>
        <div class="input_partial_main">
            <a type="button" class="btn btn-3d btn-danger btn-xs delete_main_partial"><i class="fa fa-times"></i></a>
            <div class="fancy-file-upload">
                <i class="fa fa-upload"></i>
                <input type="file" class="form-control" name="file[]" onchange="jQuery(this).next('input').val(this.value);">
                <input type="text" class="form-control" placeholder="no file selected" readonly="">
                <span class="button">Choose File</span>
            </div>
        </div>
    </div>

    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-block">
                    <?= $this->Form->create('', ['enctype' => 'multipart/form-data']); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Title*'); ?></label>
                                <?= $this->Form->control('title', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Source Name']); ?>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Description *'); ?></label>
                                <?= $this->Form->control('description', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Source Name']); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Company Name'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('company_id', $all_companies, ['id' => 'dates-field2', 'class' => 'form-control select2']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <?= __('File Attachment '); ?>
                                </label>
                                <div class="fancy-file-upload">
                                    <i class="fa fa-upload"></i>
                                    <input type="file" class="form-control" name="file[]" onchange="jQuery(this).next('input').val(this.value);">
                                    <input type="text" class="form-control" placeholder="no file selected" readonly="">
                                    <span class="button">Choose File</span>
                                </div>
                                <div>
                                    <a type="button" class="btn btn-3d btn-leaf pull-right add_input_file"><i class="fa fa-plus"></i></a >
                                </div>
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
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('.add_input_file').click(function () {
            var content = $('.input_partial').html();
            $(this).before(content);
        });
        $('#middle').delegate('.delete_main_partial', 'click', function () {
            $(this).parents('.input_partial_main').remove();
        });
    });
</script>