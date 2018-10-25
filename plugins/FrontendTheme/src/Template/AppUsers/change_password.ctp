<section>
    <div class="container">

        <div class="row">

            <div class="col-md-3 col-sm-3"></div>
            <div class="col-md-6 col-sm-6">
                <?= $this->Flash->render('auth') ?>
                <?= $this->Form->create($user, ['class' => 'sky-form boxed']) ?>
                <header>
                    <i class="fa fa-users"></i> <?= __d('CakeDC/Users', 'Please enter the new password') ?>
                </header>
                <fieldset class="m-0">
                    <?php if ($validatePassword) : ?>
                        <label><?= __d('CakeDC/Users', 'Current password'); ?></label>  
                        <label class="input mb-10">
                            <i class="ico-append fa fa-lock"></i>
                            <?= $this->Form->control('current_password', ['required' => true, 'type' => 'password', 'label' => false, 'placeholder' => 'Current password']); ?>
                            <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                        </label>

                    <?php endif; ?>
                    <label><?= __d('CakeDC/Users', 'New password'); ?></label>  
                    <label class="input mb-10">
                        <i class="ico-append fa fa-lock"></i>
                        <?= $this->Form->control('password', ['required' => true, 'type' => 'password', 'label' => false, 'placeholder' => 'Password']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                    </label>
                    <label><?= __d('CakeDC/Users', 'Confirm password'); ?></label>  
                    <label class="input mb-10">
                        <i class="ico-append fa fa-lock"></i>
                        <?=
                        $this->Form->control('password_confirm', [
                            'type' => 'password',
                            'label' => false,
                            'required' => true,
                            'placeholder' => 'Confirm password'
                        ]);
                        ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                    </label>

                </fieldset>
                <footer>
                    <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i>Submit'), ['class' => 'btn btn-primary rad-0 float-right']); ?>
                </footer>
                <?= $this->Form->end() ?>
                <!-- ALERT -->
                <?= $this->Flash->render() ?>
                <!-- /ALERT -->
            </div>
        </div>
    </div>
</section>
