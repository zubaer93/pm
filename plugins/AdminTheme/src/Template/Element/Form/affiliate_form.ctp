<?= $this->Form->create($affiliate, ['type' => 'file']); ?>

<fieldset>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-12 col-md-12">
                <?= $this->Form->control('name', [
                    'class' => 'form-control'
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?= $this->Form->control('address', [
                    'class' => 'form-control'
                ]);?>
            </div>
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?= $this->Form->control('website', [
                    'class' => 'form-control',
                    'placeholder' => 'http://'
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?= $this->Form->control('date_of_incorporation', [
                    'type' => 'text',
                    'class' => 'form-control datepicker',
                    'data-lang' => 'en',
                    'data-rtl' => 'false',
                    'data-format' => 'yyyy-mm-dd'
                ]);?>
            </div>
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <?= $this->Form->control('logo', ['class' => 'form-control', 'type' => 'file']);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('description', [
                    'class' => 'form-control',
                    'label' => __('Description')
                ]);?>
            </div>
        </div>
    </div>
</fieldset>

<div class="row">
    <div class="col-md-12">
        <?= $this->Form->button(__('SAVE AFFILIATE'), [
            'type' => 'submit',
            'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
        ]); ?>
    </div>
</div>

<?= $this->Form->end(); ?>
