<?php

use Cake\Core\Configure;

?>

<!-- -->
<section>
    <div class="container">

        <!-- ALERT -->
        <?= $this->Flash->render() ?>
        <!-- /ALERT -->
        <div class="row">

            <!-- LOGIN -->
            <div class="col-md-6 col-sm-6">
                <!-- register form -->
                <?= $this->Form->create($user, ['class' => 'm-0 sky-form boxed']); ?>
                <header>
                    <i class="fa fa-users"></i> <?= __('Register') ?>
                </header>
                <fieldset class="m-0">
                    <label class="input mb-10">
                        <i class="ico-append fa fa-user"></i>
                        <?= $this->Form->control('username', ['required' => true, 'type' => 'text', 'label' => false, 'placeholder' => 'Username']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Needed to verify your account') ?></b>
                    </label>
                    <label class="input mb-10">
                        <i class="ico-append fa fa-envelope"></i>
                        <?= $this->Form->control('email', ['required' => true, 'type' => 'email', 'label' => false, 'placeholder' => 'Email address']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Needed to verify your account') ?></b>
                    </label>
                    <label class="select mb-10 mt-20">
                        <i class=""></i>
                        <?= $this->Form->select('investment_style_id', \App\Model\Service\Core::$investmentStyle, ['empty' => 'Select investment style', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Select investment style') ?></b>
                    </label>
                    <label class="select mb-10 mt-20">
                        <i class=""></i>
                        <?= $this->Form->select('experince_id', \App\Model\Service\Core::$experience, ['empty' => 'Select experience', 'required' => true]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Experience') ?></b>
                    </label>
                    <label class="input mb-10">
                        <i class=""></i>
                        <?= $this->Form->control('date_of_birth', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker reg-datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => __('Date of birth')]); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Date of Birth') ?></b>
                    </label>
                    <label class="input mb-10">
                        <i class="ico-append fa fa-lock"></i>
                        <?= $this->Form->control('password', ['required' => true, 'type' => 'password', 'label' => false, 'placeholder' => 'Password']); ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                    </label>

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

                    <div class="row mb-10">
                        <div class="col-md-6">
                            <label class="input">
                                <?= $this->Form->control('first_name', ['label' => false, 'placeholder' => 'First name']); ?>
                            </label>
                        </div>
                        <div class="col col-md-6">
                            <label class="input">
                                <?= $this->Form->control('last_name', ['label' => false, 'placeholder' => 'Last name']); ?>
                            </label>
                        </div>
                    </div>


                    <div class="mt-30">

                        <?php if (Configure::read('Users.Tos.required')) { ?>
                            <label class="checkbox m-0">

                                <?= $this->Form->checkbox('tos', ['class' => 'checked-agree', 'label' => false, 'required' => true]); ?>
                                <i></i><?= __('I agree to the ') ?>
                                <a href="#!" data-toggle="modal" data-target="#termsModal">
                                    <?= __('Terms of Service') ?>
                                </a>
                            </label>
                        <?php } ?>

                    </div>
                </fieldset>

                <div class="row mb-20">
                    <div class="col-md-12">
                        <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i>' . __('REGISTER')), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>

                <?= $this->Form->end() ?>
                <!-- /register form -->

            </div>
            <!-- /LOGIN -->

            <!-- SOCIAL LOGIN -->
            <div class="col-md-6 col-sm-6">
                <div class="teste">
                </div>
                <?= $this->Form->create($user, ['class' => 'm-0 sky-form boxed']); ?>

                <header class="fs-18 mb-20">
                    <?= $this->Html->link(__d('CakeDC/Users', 'Back to login!'), ['_name' => 'login']); ?>
                    <?= __('or Register using your favourite social network') ?>
                </header>

                <fieldset class="m-0">

                    <div class="row">

                        <div class="col-md-8 offset-md-2">
                            <?= $this->User->socialLogin('facebook', ['class' => 'btn-block mb-10', 'title' => 'Sign up with Facebook']) ?>
                            <?= $this->User->socialLogin('google', ['class' => 'btn-block mb-10', 'title' => 'Sign up with Google']) ?>
                        </div>
                    </div>

                </fieldset>

                <footer>
                </footer>

                <?= $this->Form->end() ?>

            </div>
            <!-- /SOCIAL LOGIN -->

        </div>


    </div>
</section>
<!-- / -->


<!-- MODAL -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModal"><?= __('Terms &amp; Conditions') ?></h4>
            </div>

            <div class="modal-body modal-short">
                <?= isset($page->body) ? $page->body : ''; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-primary" id="terms-agree"><i
                            class="fa fa-check"></i> <?= __('I Agree') ?>
                </button>

                <?=
                $this->Html->link($this->Html->tag('i',
                    $this->Html->tag('span', 'Print', ['class' => 'hidden-xs-down']),
                    ['class' => 'fa fa-print']), [
                    '_name' => 'dynamic',
                    'name' => 'terms-of-service'
                ], [
                    'escape' => false,
                    'class' => 'btn btn-danger float-left',
                    'target' => "_blank",
                    'rel' => "nofollow"
                ]);
                ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /MODAL -->
<?php $this->Html->scriptStart(['block' => true]); ?>
/**
Checkbox on "I agree" modal Clicked!
**/
$(function(){
$("#terms-agree").click(function () {
$('#termsModal').modal('toggle');

// Check Terms and Conditions checkbox if not already checked!
if (!$("#checked-agree").checked) {
$("input.checked-agree").prop('checked', true);
}
});
});

<?php $this->Html->scriptEnd(); ?>
