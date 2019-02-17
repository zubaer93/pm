<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Cake\Core\Configure;

?>
<section>
    <div class="container">

        <!-- ALERT -->
        <?= $this->Flash->render() ?>
        <!-- /ALERT -->
        <div class="row">
            <!-- LOGIN -->
            <div class="col-md-6 col-sm-6">

                <!-- login form -->
                <?= $this->Flash->render('auth') ?>
                <?= $this->Form->create('', ['class' => 'sky-form boxed']) ?>

                <header class="fs-18 mb-20">
                    <?= __('Welcome back!') ?>
                </header>


                <fieldset class="m-0">
                    <label class="input mb-10">
                        <i class="ico-append fa fa-user"></i>
                        <?= $this->Form->control('username', ['label' => false, 'type' => 'text', 'required' => true, 'placeholder' => __('Username or Email')]) ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Needed to verify your account') ?></b>
                    </label>

                    <label class="input mb-10">
                        <i class="ico-append fa fa-lock"></i>
                        <?= $this->Form->control('password', ['label' => false, 'type' => 'password', 'required' => true, 'placeholder' => __('Password')]) ?>
                        <b class="tooltip tooltip-bottom-right"><?= __('Only latin characters and numbers') ?></b>
                    </label>

                    <div class="clearfix note mb-30">
                        <?php
                        $registrationActive = Configure::read('Users.Registration.active');
                        if (Configure::read('Users.Email.required')) {
                            echo $this->Html->link(__d('CakeDC/Users', 'Forgot Password?'), ['_name' => 'requestResetPassword'], ['class' => 'float-right']);
                        }
                        ?>
                    </div>
                    <label class="checkbox fw-300">
                        <input type="hidden"  value="0">
                        <input type="checkbox" value="1" id="remember-me">
                        <i></i> <?= __('Keep me logged in') ?>
                    </label>

                </fieldset>

                <footer>
                    <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i>OK, LOG IN'), ['class' => 'btn btn-primary rad-0 float-right']); ?>
                </footer>

                <?= $this->Form->end() ?>
                <!-- /login form -->

            </div>
            <!-- /LOGIN -->

            <!-- SOCIAL LOGIN -->
            <div class="col-md-6 col-sm-6">
                <?= $this->Form->create('social', ['class' => 'sky-form boxed']) ?>

                    <header class="fs-18 mb-20">
                        <?php
                        $registrationActive = Configure::read('Users.Registration.active');
                        if ($registrationActive) {
                            echo $this->Html->link(__d('CakeDC/Users', 'Register'), ['_name' => 'register']);
                        }
                        ?>
                        <?= __(' or Sign In using your favourite social network') ?>
                    </header>

                    <fieldset class="m-0">

                        <div class="row">

                            <div class="col-md-8 offset-md-2">
                                <?= $this->Form->hidden('username', ['label' => false, 'type' => 'text', 'required' => true, 'placeholder' => 'Username']) ?>
                                <?= $this->Form->hidden('password', ['label' => false, 'type' => 'password', 'required' => true, 'placeholder' => 'Password']) ?>

                                <?= $this->User->socialLogin('facebook',['class' => 'btn-block mb-10']) ?>
                                <?= $this->User->socialLogin('google', ['class' => 'btn-block mb-10']) ?>
                            </div>
                        </div>

                    </fieldset>
                <?= $this->Form->end() ?>

            </div>
            <!-- /SOCIAL LOGIN -->
        </div>
    </div>
</section>
<!-- / -->
