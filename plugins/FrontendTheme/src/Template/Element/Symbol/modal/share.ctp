<div class="modal" id="modal_share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php
            $formOptions = [
                'id' => 'formMessageModal',
                'url' => ['controller' => 'Messages', 'action' => 'postModal']
            ];
            echo $this->Form->create('Newsfeed', $formOptions);
            ?>
            <div class="modal-header">
                <span><?= __('Share message'); ?></span>
            </div>
            <div class="modal-body">
                <?php
                $config = [
                    'id' => 'message-modal',
                    'class' => 'message-box-modal form-control h-100',
                    'placeholder' => 'Share an idea (use ! before ticker: e.g !SYMBL)',
                    'data-maxlength' => '100',
                    'onkeyup' => 'countCharModal(this)'
                ];
                echo $this->Form->textarea('message', $config);
                ?>
                <?= $this->Form->hidden('parent_id', ['type' => 'text', 'class' => 'parent_message_id']); ?>
                <?= $this->Form->hidden('parent_img_url', ['type' => 'text', 'class' => 'parent_img_url']); ?>
                <?= $this->Form->hidden('user_id', ['type' => 'text', 'value' => $userId]); ?>
                <?= $this->Form->hidden('company_id', ['type' => 'text', 'value' => (isset($companyInfo['id'])) ? $companyInfo['id'] : '']); ?>
                <?= $this->Form->hidden('trader_id', ['type' => 'text', 'value' => (isset($exchangeInfo['id'])) ? $exchangeInfo['id'] : '']); ?>
                <?= $this->Form->hidden('market', ['type' => 'text', 'value' => $currentLanguage]); ?>

                <div class="text-muted text-right mt-3 fs-12">
                    <span class="words-to-write-modal">200</span>
                </div>

                <div class="user_message">

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel'); ?></button>
                <button class="btn btn-primary btn-ok share-modal-button" type="submit"><i class="fa fa-share-alt"></i><?= __('Share'); ?></button>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>