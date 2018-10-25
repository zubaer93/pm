<ul class="nav nav-tabs nav-top-border" role="tablist">
    <a class="active" href="#info" data-toggle="tab" role="tablist"><?= __('Personal Info'); ?></a>
    <a href="#avatar" data-toggle="tab" role="tablist"><?= __('Avatar'); ?></a>
    <a href="#password" data-toggle="tab" role="tablist"><?= __('Password'); ?></a>
    <a href="#watchlists" data-toggle="tab" role="tablist"><?= __('Watchlists'); ?></a>
</ul>

<div class="tab-content mt-20">
    <!-- PERSONAL INFO TAB -->
    <?= $this->element('Users/personal_info'); ?>
    <!-- /PERSONAL INFO TAB -->

    <!-- AVATAR TAB -->
    <?= $this->element('Users/avatar'); ?>
    <!-- /AVATAR TAB -->

    <!-- PASSWORD TAB -->
    <?= $this->element('Users/password'); ?>
    <!-- /PASSWORD TAB -->

    <!-- PASSWORD TAB -->
    <?= $this->element('Users/watchlists'); ?>
    <!-- /PASSWORD TAB -->
</div>