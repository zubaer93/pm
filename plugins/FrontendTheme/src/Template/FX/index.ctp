<?php
$this->Html->script(
        [
    'forex/forex.js'
        ], ['block' => 'script']
);
?>
<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container" data>
        <div class="box-message">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-30 mt-30">
                <?= $this->element('FX/table'); ?>
            </div>
        </div>
    </div>
</section>