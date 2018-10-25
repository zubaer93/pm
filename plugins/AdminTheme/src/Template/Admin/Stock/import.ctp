<section id="middle">
    <header id="page-header">
        <h1><?=__('Import CSV');?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Company'), '#'); ?></li>
            <li class="active"><?=__('Import Stock');?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-body">
                <?= $this->Form->create('' . $id, ['type' => 'file']); ?>
                <div class="fancy-file-upload fancy-file-default">
                    <i class="fa fa-upload"></i>
                    <input type="file" class="form-control" required="true" name="file" onchange="jQuery(this).next('input').val(this.value);"/>
                    <input type="text" class="form-control" placeholder="<?=__('no file selected');?>" readonly=""/>
                    <span class="button">Choose File</span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?=
                            $this->Form->button(__('IMPORT STOCK'), ['type' => 'submit',
                            'class' => 'btn btn-primary'
                        ]);
                        ?>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>