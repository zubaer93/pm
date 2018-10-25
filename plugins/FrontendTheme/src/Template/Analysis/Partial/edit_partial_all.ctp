<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="mySmallModalLabel">Edit Analysis</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">

        <?php
        $formOptions = [
            'class' => 'analysi_form',
            'url' => ['_name' => 'add_analysi', 'symbol' => $companyInfo['symbol']]
        ];
        echo $this->Form->create('Analysis', $formOptions);
        ?>
        <input type="hidden" name="row_id" value="<?= $requestData; ?>">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-10">
                        <?= $this->Form->textarea('text', ['type' => 'text', 'data-height' => '400', 'data-lang' => 'en-US', 'class' => 'summernote form-control text_analysis', 'placeholder' => ((isset($analysis)) ? $analysis['text'] : 'My notes/Analysis'), 'value' => (isset($analysis)) ? $analysis['text'] : '']); ?>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                <div class="row">
                    <div class="col-md-12">
                        <label class="select">
                            <?= __('Name'); ?>
                            <?= $this->Form->control('name', ['required' => true, 'label' => false, 'placeholder' => ((isset($analysis)) ? $analysis['name'] : 'Name'), 'value' => ((isset($analysis)) ? $analysis['name'] : ''), 'class' => 'form-control', '']); ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="select">
                            <?= __('Type'); ?>
                            <?= $this->Form->select('type', \App\Model\Service\Core::$investmentPreferences, ['default' => (isset($analysis) ? $analysis['type'] : null), 'class' => 'form-control select2', 'required' => true]); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="select">
                            <?= __('Symbol'); ?>
                            <?php
                            if (isset($analysis['analysis_symbols'])):
                                $analysis_symbols = '';
                                foreach ($analysis['analysis_symbols'] as $symbols):
                                    $analysis_symbols .= $symbols['name'] . ' ';
                                endforeach;
                            else:
                                $analysis_symbols = $symbol;
                            endif;
                            ?>
                            <?= $this->Form->control('symbol', ['required' => true, 'label' => false, 'class' => 'form-control', 'placeholder' => $analysis_symbols, 'value' => $analysis_symbols]); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="select">
                            <?= __('Watch List'); ?>
                            <?php
                            if (isset($analysis['analysis_watch_list'])):
                                $analysis_watch_list = [];
                                foreach ($analysis['analysis_watch_list'] as $watch):
                                    $analysis_watch_list[] = $watch['watch_list_group_id'];
                                endforeach;
                            else:
                                $analysis_watch_list = [];
                            endif;
                            ?>
                            <?= $this->Form->select('watch_list', $watchlistList_array, ['default' => $analysis_watch_list, 'multiple' => 'multiple', 'class' => 'form-control select2 company']); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="select">
                            <?= __('News'); ?>
                            <?php
                            if (isset($analysis['analysis_news'])):
                                $analysis_array = [];
                                foreach ($analysis['analysis_news'] as $news):
                                    $analysis_array[] = $news['news_id'];
                                endforeach;
                            else:
                                $analysis_array = [];
                            endif;
                            ?>
                            <?= $this->Form->select('news', $news_array, ['default' => $analysis_array, 'multiple' => 'multiple', 'class' => 'form-control select2 company']); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="input">
                            <?= __('Tags'); ?>
                            <?php
                            if (isset($analysis['analysis_tags'])):
                                $analysis_tags = '';
                                foreach ($analysis['analysis_tags'] as $tags):
                                    $analysis_tags .= $tags['name'];
                                endforeach;
                            else:
                                $analysis_tags = '';
                            endif;
                            ?>
                            <?= $this->Form->control('tags', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Tags', 'value' => $analysis_tags]); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>

                <?php
                $buttonOptions = [
                    'type' => 'submit',
                    'escape' => false,
                    'class' => 'btn btn-primary'
                ];
                echo $this->Form->button(__('<i class="fa fa-check"></i> SAVE'), $buttonOptions);
                ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
