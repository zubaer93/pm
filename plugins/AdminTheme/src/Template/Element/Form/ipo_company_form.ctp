<?= $this->Form->create('saveIpoCompany'); ?>
<fieldset>
    <?=
    $this->Form->control(__('Information *'), ['type' => 'hidden',
        'value' => 'contact_send',
        'name' => 'action'
    ]);
    ?>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <label><?= __('Ipo Market *'); ?></label>
                <select required name="contact[ipo_market]" class="form-control">
                    <?php $ipo_market_id = (isset($ipoCompany) ? $ipoCompany['ipo_market_id'] : "");?>
                    <option value="" disabled><?= __('Select Ipo Market'); ?></option>
                    <?php foreach ($all_ipo_markets as $ipo_market): ?>
                        <?php
                        if ($ipo_market['id'] == $ipo_market_id):
                            $selected = 'selected';
                        else:
                            $selected = '';
                        endif;
                        ?>
                        <option <?= $selected; ?>  value="<?= $ipo_market['id'] ?>"><?= $ipo_market['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12 col-sm-12">
                <?=
                $this->Form->control(__('Name *'), ['required' => true, 'type' => 'text',
                    'name' => 'contact[name]',
                    'class' => 'form-control required',
                    'value' => isset($ipoCompany) ? $ipoCompany['name'] : ""
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <label><?= __('About'); ?> </label>
            <?=
            $this->Form->textarea('contact[about]', ['type' => 'text', 'data-height' => '200',
                'data-lang' => 'en-US',
                'class' => 'form-control summernote',
                'placeholder' => __('About'),
                'value' => isset($ipoCompany) ? $ipoCompany['about'] : ""
            ]);
            ?>
        </div>
    </div>

</fieldset>

<div class="row">
    <div class="col-md-12">
        <?=
        $this->Form->button(__('SAVE IPO COMPANY'), ['type' => 'submit',
            'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
        ]);
        ?>
    </div>
</div>

<?= $this->Form->end(); ?>
