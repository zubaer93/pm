<!-- LEFT COLUMNS -->
<?php
$this->Html->script(
        [
    'symbol/watchlist'
        ], ['block' => 'script']
);
?>
<div class="col-lg-9 col-md-9 col-sm-8">

    <div class="row"><!-- item -->
        <div class="col-lg-12 col-md-12 col-sm-12"><!-- company detail -->
            <h4 class="mb-5 fs-25"><?= $user->fullName; ?></h4>
            <div class="container">
                <div class="row">
                    <?= $this->element('Users/rating'); ?>
                </div>
            </div>
            <h5 class="mb-20 fs-16" style="color: #676767;">
                <?= '@' . $user->username; ?>
                <span class="fs-14" style="display: block; color: #676767;">Joined <?= date("F d Y", strtotime($user->created)); ?></span>
            </h5>
            <blockquote>
                 <p class="fs-14"><?= $user->Bio; ?></p>
                <cite><?= __('biography'); ?></cite>
            </blockquote>
        </div>
        <!-- PERSONAL Rating -->

        <!-- /PERSONAL Rating -->
    </div><!-- /item -->

    <hr />

</div>