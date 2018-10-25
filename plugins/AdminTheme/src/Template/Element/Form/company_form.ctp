<?= $this->Html->css('AdminTheme./assets/css/custom', ['block' => 'css']); ?>

<?= $this->Form->create($company, ['type' => 'file']); ?>
<fieldset>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('name', [
                    'class' => 'form-control',
                    'label' => __('Name *')
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('symbol', [
                    'class' => 'form-control',
                    'label' => __('Symbol *')
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('sector', [
                    'class' => 'form-control',
                    'label' => __('Sector *')
                ]); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-6 col-sm-6">
                <?= $this->Form->control('exchange_id', [
                    'options' => $exchanges,
                    'label' => __('Exchange *'),
                    'class' => 'form-control'
                ]);?>
            </div>

            <div class="col-md-6 col-sm-6">
                <?= $this->Form->control('ipoyear', [
                    'type' => 'text',
                    'class' => 'form-control datepicker',
                    'data-lang' => 'en',
                    'data-rtl' => 'false',
                    'data-format' => 'yyyy',
                    'label' => __('Year *'),
                    'placeholder' => date('Y')
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('industry', [
                    'class' => 'form-control',
                    'label' => __('Industry *')
                ]);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('photo', ['class' => 'form-control', 'type' => 'file']);?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12 js-company-url" data-company="<?= $this->Url->build(['_name' => 'company_get_company']); ?>">
                <?= $this->Form->control('affiliates._ids', ['label' => __('Affiliated Companies'), 'options' => $affiliates, 'class' => 'form-control company js-company-data select2']); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <div class="js-fields">
                    <div class="row mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label for="key-people">Key People</label>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']), 'javascript:;', [
                                'class' => 'btn btn-success js-add-element pull-right',
                                'escape' => false
                            ]);?>
                        </div>
                    </div>
                    <?php $i = 0; ?>
                    <?php if (!empty($company->key_people)): ?>
                        <?php foreach ($company->key_people as $keyPerson): ?>
                            <div class="row js-people-<?= $i; ?>">
                                <div class="clearfix mt15">
                                    <div class="col-md-5">
                                        <?= $this->Form->control('key_people.' . $i . '.name', ['class' => 'form-control']);?>
                                    </div>
                                    <div class="col-md-5">
                                        <?= $this->Form->control('key_people.' . $i . '.title', ['class' => 'form-control']);?>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-danger js-remove-element mt-24 pull-right" data-index="<?= $i; ?>" type="button">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-5">
                                        <?= $this->Form->control('key_people.' . $i . '.age', ['class' => 'form-control']);?>
                                    </div>
                                    <div class="col-md-5">
                                        <?= $this->Form->control('key_people.' . $i . '.photo', ['class' => 'form-control', 'type' => 'file']);?>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="row js-people-<?= $i; ?>">
                            <div class="clearfix mt15">
                                <div class="col-md-5">
                                    <?= $this->Form->control('key_people.' . $i . '.name', ['class' => 'form-control']);?>
                                </div>
                                <div class="col-md-5">
                                    <?= $this->Form->control('key_people.' . $i . '.title', ['class' => 'form-control']);?>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-danger js-remove-element mt-24 pull-right" data-index="<?= $i; ?>" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <?= $this->Form->control('key_people.' . $i . '.age', ['class' => 'form-control']);?>
                                </div>
                                <div class="col-md-5">
                                    <?= $this->Form->control('key_people.' . $i . '.photo', ['class' => 'form-control', 'type' => 'file']);?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control('company_overview', [
                    'class' => 'form-control',
                    'label' => __('Company Overview')
                ]);?>
            </div>
        </div>
    </div>
</fieldset>

<div class="row">
    <div class="col-md-12">
        <?= $this->Form->button(__('SAVE COMPANY'), [
            'type' => 'submit',
            'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
        ]); ?>
    </div>
</div>

<?= $this->Form->end(); ?>

<?= $this->Html->script('AdminTheme./assets/js/Views/Companies/form', ['block' => 'script']); ?>
<script type="text/javascript">
    $(document).ready(function () {
        new KeyPeople();
    });

    function formatRepo(repo) {
        if (repo.loading) {
            return repo.name;
        }

        var markup = repo.name;
        return markup;
    }

    function formatRepoSelection(repo) {
        return repo.name || repo.text;
    }

    function convertToJson(data) {
        var array = [];
        $.each(data, function (index, value) {
            array.push({
                id: index,
                name: value
            });
        });

        return array;
    }
</script>