<section id="middle">

    <header id="page-header">
        <h1><?= __('Add User'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('User'), ['_name' => 'users_list']); ?></li>
            <li class="active"><?= __('Add User'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <?= $this->Form->create(NULL, ['url' => '']); ?>
                        <div class="form-group">
                            <label class="form-control-label"><?= __d('CakeDC/Users', 'First Name') ?></label>
                            <?= $this->Form->text('first_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'First name', 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __d('CakeDC/Users', 'Last Name') ?></label>
                            <?= $this->Form->text('last_name', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Last name', 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __d('CakeDC/Users', 'Username') ?></label>
                            <?= $this->Form->text('username', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Username', 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __('E-mail'); ?> </label>
                            <?= $this->Form->text('email', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Email', 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"> <?= __('Investment style'); ?> </label>
                            <?= $this->Form->select('investment_style_id', \App\Model\Service\Core::$investmentStyle, ['empty' => 'Select investment style', 'required' => true, 'class' => 'form-control pointer required']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"> <?= __('Experience') ?> </label>
                            <?= $this->Form->select('experince_id', \App\Model\Service\Core::$experience, ['empty' => 'Select experience', 'required' => true, 'class' => 'form-control pointer required']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"> <?= __('Date of birth') ?> </label>
                            <?= $this->Form->control('date_of_birth', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => __('Date of birth'), 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __('Bio'); ?></label>
                            <?= $this->Form->textarea('description', ['label' => false, 'class' => 'form-control', 'placeholder' => 'Bio', 'value' => '']); ?>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __('Password'); ?> </label>
                            <?= $this->Form->control('password', ['required' => true, 'type' => 'password', 'label' => false, 'class' => 'form-control', 'placeholder' => 'Password']); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label"><?= __('Password Confirm'); ?> </label>
                            <?=
                            $this->Form->control('password_confirm', [
                                'type' => 'password',
                                'label' => false,
                                'required' => true,
                                'placeholder' => 'Confirm password',
                                'class' => 'form-control'
                            ]);
                            ?>
                        </div>
                        <div class="margiv-top10">
                            <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i> Save'), ['class' => 'btn btn-primary']) ?>
                            <?= $this->Form->button(__d('CakeDC/Users', __('Cancel')), ['type' => 'reset', 'class' => 'btn btn-default']) ?>
                        </div>
                        <?= $this->Form->end() ?>
                        <?= $this->Flash->render() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

