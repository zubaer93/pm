<?= $this->Form->create(''); ?>
    <fieldset>
        <div class="row">
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <?=
                    $this->Form->control(__('Name *'), ['required' => true, 'type' => 'text',
                        'name' => 'contact[name]',
                        'class' => 'form-control required',
                        'value' => isset($researchMarket) ? $researchMarket['name'] : ""
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->button(__('SAVE RESEARCH MARKET'),
                [
                    'type' => 'submit',
                    'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
                ]
            ); ?>
        </div>
    </div>
<?= $this->Form->end(); ?>