<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container-fluid mt-10 mb-80">
        <div class="box-message">
            <div class="box-light">
                <div class="text-center mt-10 mb-20">
                    <h3><?= __('Initial Public Offering'); ?></h3>
                </div>
                <div class="row custom-tabs-container">
                    <?php if ($data): ?>
                        <!-- LEFT -->
                        <div class="col-lg-3 col-md-3 col-sm-3 order-sm-1 order-md-1 order-lg-1 custom-tab-left-container">
                            <!-- CATEGORIES -->
                            <div class="custom-tabs">
                                <ul class="uppercase">
                                    <?= $this->element('Ipo/ipo_market'); ?>
                                </ul>
                            </div>
                            <!-- /CATEGORIES -->
                        </div>
                        <!-- /LEFT -->

                        <!-- RIGHT -->
                        <div class="col-lg-9 col-md-9 col-sm-9 order-sm-2 order-md-2 order-lg-2 custom-tab-right-container">
                            <ul class="nav nav-tabs">
                                <?= $this->element('Ipo/ipo_company'); ?>
                            </ul>

                            <div class="tab-content tab-content-cutom">
                                <?= $this->element('Ipo/ipo_company_content'); ?>
                            </div>
                        </div>
                        <!-- /RIGHT -->
                    <?php else: ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="text-center"><?= __("There is no market in IPO"); ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- MOBILE -->
                <div class="row custom-tabs-container mobile">
                    <?php if ($data): ?>

                        <div class="dropdown">
                            <?= $this->Html->link(__("Select Market"), "#", ['class' => 'dropdown-toggle']) ?>
                            <ul class="dropdown-menu uppercase">
                                <?= $this->element('Ipo/ipo_market'); ?>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <?= $this->Html->link(__("Select Company"), "#", ['class' => 'dropdown-toggle']) ?>
                            <ul class="dropdown-menu uppercase">
                                <?= $this->element('Ipo/ipo_company'); ?>
                            </ul>
                        </div>

                        <div class="tab-content tab-content-cutom">
                            <?= $this->element('Ipo/ipo_company_content'); ?>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="text-center"><?= __("There is no market in IPO"); ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- /MOBILE -->
            </div>
        </div>
    </div>
</section>
<?= $this->Html->script('/js/custom') ?>