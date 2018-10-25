<div class="tab-pane active" id="info">
    <?= $this->Form->create($user, ['url' => ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'editPost'], ['_name' => 'edit']]); ?>
    <?= $this->Flash->render(); ?>
    <div class="form-group">
        <?= $this->Form->hidden('id', ['label' => false, 'value' => $user->id]) ?>
        <label class="form-control-label"><?= __d('CakeDC/Users', 'First Name') ?></label>
        <?= $this->Form->text('first_name', ['label' => false, 'class' => 'form-control', 'placeholder' => $user->first_name, 'value' => $user->first_name]); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"><?= __d('CakeDC/Users', 'Last Name') ?></label>
        <?= $this->Form->text('last_name', ['label' => false, 'class' => 'form-control', 'placeholder' => $user->last_name, 'value' => $user->last_name]); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"><?= __d('CakeDC/Users', 'Username') ?></label>
        <?= $this->Form->text('username', ['label' => false, 'class' => 'form-control', 'placeholder' => $user->username, 'value' => $user->username]); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"><?= __('E-mail'); ?> </label>
        <?= $this->Form->text('email', ['label' => false, 'class' => 'form-control', 'placeholder' => $user->email, 'value' => $user->email]); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"> <?= __('Investment style'); ?> </label>
        <?= $this->Form->select('investment_style_id', \App\Model\Service\Core::$investmentStyle, ['default' => $user->investment_style_id, 'empty' => 'Select investment style', 'required' => true, 'class' => 'form-control pointer required']); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"> <?= __('Experience') ?> </label>
        <?= $this->Form->select('experince_id', \App\Model\Service\Core::$experience, ['default' => $user->experience_id, 'empty' => 'Select experience', 'required' => true, 'class' => 'form-control pointer required']); ?>

    </div>

    <div class="form-group">
        <label class="form-control-label"> <?= __('Date of birth') ?> </label>
        <?= $this->Form->control('date_of_birth', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => $user->date_of_birth, 'value' => date('Y-m-d', strtotime($user->date_of_birth))]); ?>

    </div>
    <div class="form-group">
        <label class="form-control-label"><?= __('Bio'); ?></label>
        <?= $this->Form->textarea('description', ['label' => false, 'class' => 'form-control', 'placeholder' => $user->bio, 'value' => $user->bio]); ?>

    </div>
    <div class="margiv-top10">
        <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i> Save'), ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->button(__d('CakeDC/Users', __('Cancel')), ['type' => 'reset', 'class' => 'btn btn-default']) ?>
    </div>
    <?= $this->Form->end(); ?>

</div>
