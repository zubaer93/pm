<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']); ?>
<div class="container-fluid">
    <div class="row">
        <!-- LEFT -->
        <?= $this->element('Users/sidenav'); ?>
        <!-- RIGHT -->
        <div class="col-lg-8 col-md-8 col-sm-7 mb-80">
            <!-- PERSONAL INFO TAB -->
            <?= $this->element('Users/settings'); ?>
            <!-- /PERSONAL INFO TAB -->
        </div>
    </div>
</div>