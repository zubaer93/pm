<div class="tab-pane fade" id="password">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create($user, ['url' => ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'changePassword']]) ?>
        <div class="form-group">
            <label class="form-control-label"><?= __('New Password') ?></label>
            <?= $this->Form->control('password', ['type' => 'password','label' => false, 'class' => 'form-control', 'value' => false]); ?>
        </div>
        <div class="form-group">
            <label class="form-control-label"><?= __('Re-type New Password') ?></label>
            <?= $this->Form->control('password_confirm', ['type' => 'password','label' => false, 'class' => 'form-control']); ?>
        </div>
        <div class="margiv-top10">
            <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i> '. __('SUBMIT')), ['class' => 'btn btn-primary']); ?>
            <button type="reset" class="btn btn-default"><?= __('Cancel') ?></button>
        </div>
    <?= $this->Form->end(); ?>
</div>