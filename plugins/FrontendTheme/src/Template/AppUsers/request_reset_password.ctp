<section>
    <div class="container">

        <div class="row">
            <div class="col-md-3 col-sm-3"></div>
            <div class="col-md-6 col-sm-6">
                <?= $this->Flash->render('auth') ?>
                <?= $this->Form->create('User', ['class' => 'sky-form boxed']) ?>
                <header class="fs-18 mb-20">
                    <?= __('Please enter your email to reset your password') ?>
                </header>

                <fieldset class="m-0">
                    <label class="input mb-10">
                        <i class="ico-append fa fa-user"></i>
                        <?= $this->Form->control('reference', ['label' => false, 'type' => 'text', 'required' => true, 'placeholder' => __('Email')]) ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Please enter your email to reset your password') ?></b>
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
