<?= $this->Form->create('saveResearchCompany'); ?>
<fieldset>
    <?= $this->Form->control(__('Information *'), ['type' => 'hidden',
        'value' => 'contact_send',
        'name' => 'action'
    ]);
    ?>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <label><?=__('Research Market *');?></label>
                <select required name="contact[research_market]" class="form-control" value=<?= isset($researchCompany) ? $researchCompany['research_market_id'] : "" ?>>
                    <option value="" disabled><?= __('Select Research Market');?></option>
                    <?php foreach ($all_research_markets as $research_market): ?>
                        <option value="<?= $research_market['id'] ?>"><?= $research_market['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?= $this->Form->control(__('Name *'), ['required' => true,'type' => 'text',
                    'name' => 'contact[name]',
                    'class' => 'form-control required',
                    'value' => isset($researchCompany) ? $researchCompany['name'] : ""
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <label><?=__('About'); ?> </label>
            <?= $this->Form->textarea('contact[about]', ['type' => 'text', 'data-height' => '200',
                'data-lang' => 'en-US',
                'class' => 'form-control summernote',
                'placeholder' => __('About'),
                'value' => isset($researchCompany) ? $researchCompany['about'] : ""
            ]);
            ?>
        </div>
    </div>

</fieldset>

<div class="row">
    <div class="col-md-12">
        <?= $this->Form->button(__('SAVE RESEARCH COMPANY'), ['type' => 'submit',
            'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
        ]);
        ?>
    </div>
</div>

<?= $this->Form->end(); ?>
