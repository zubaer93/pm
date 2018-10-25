<ul class="nav nav-tabs">
    <li class="active">
        <a href="#tab1_nobg" data-toggle="tab">
            <i class="fa fa-info-circle"></i> <?= __('Personal Info') ?>
        </a>
    </li>
    <li class="">
        <a href="#tab2_nobg" data-toggle="tab">
            <i class="fa fa-photo"></i> <?= __('Avatar') ?>
        </a>
    </li>
<!--    <li class="">
        <a href="#tab3_nobg" data-toggle="tab">
            <i class="fa fa-cogs"></i> <?= __('Password') ?>
        </a>
    </li>-->
</ul>
<div class="tab-content transparent">

    <!-- PERSONAL INFO TAB -->

    <div id="tab1_nobg" class="tab-pane active ">
        <?= $this->element('Users/personal_info'); ?>
    </div>
    <!-- /PERSONAL INFO TAB -->

    <!-- AVATAR TAB -->

    <div id="tab2_nobg" class="tab-pane">
        <?= $this->element('Users/avatar'); ?>
    </div>
    <!-- /AVATAR TAB -->

    <!-- PASSWORD TAB -->

<!--    <div id="tab3_nobg" class="tab-pane">
        <?= $this->element('Users/password'); ?>
    </div>-->
    <!-- /PASSWORD TAB -->
</div>