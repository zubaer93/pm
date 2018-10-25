<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding-top: 10px;">
    <div class="container-fluid">
        <div class="box-message">
            <div class="box-light">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-0">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <h2 class="text-center"><?= __('Research'); ?></h2>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-6">
                        <div class="mt-p-2 float-right">  
                            <?=
                            $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fa fa-id-card-o']) . __('Prequalification'), [
                                '_name' => 'market_prequalification'
                                    ], [
                                'class' => 'btn btn-primary btn-sm ',
                                'tabindex' => '-1',
                                'escape' => false
                                    ]
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row custom-tabs-container">
                    <?php if ($data): ?>
                        <!-- LEFT -->
                        <div class="col-lg-3 col-md-3 col-sm-3 order-sm-1 order-md-1 order-lg-1 custom-tab-left-container">
                            <!-- CATEGORIES -->
                            <div class="custom-tabs">
                                <ul class="uppercase">
                                    <?= $this->element('Research/research_market'); ?>
                                </ul>
                            </div>
                            <!-- /CATEGORIES -->
                        </div>
                        <!-- /LEFT -->

                        <!-- RIGHT -->
                        <div class="col-lg-9 col-md-9 col-sm-9 order-sm-2 order-md-2 order-lg-2 custom-tab-right-container">
                            <ul class="nav nav-tabs">
                                <?= $this->element('Research/research_company'); ?>
                            </ul>

                            <div class="tab-content tab-content-cutom">
                                <?= $this->element('Research/research_company_content'); ?>
                            </div>
                        </div>
                        <!-- /RIGHT -->
                    <?php else: ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="text-center"><?= __("There is no market in RESEARCH"); ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- MOBILE -->
                <div class="row custom-tabs-container mobile">
                    <?php if ($data): ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="dropdown">
                                <?= $this->Html->link(__("Select Market"), "#", ['class' => 'dropdown-toggle']) ?>
                                <ul class="dropdown-menu uppercase">
                                    <?= $this->element('Research/research_market'); ?>
                                </ul>
                            </div>

                            <div class="dropdown">
                                <?= $this->Html->link(__("Select Company"), "#", ['class' => 'dropdown-toggle']) ?>
                                <ul class="dropdown-menu uppercase">
                                    <?= $this->element('Research/research_company'); ?>
                                </ul>
                            </div>

                            <div class="tab-content tab-content-cutom">
                                <?= $this->element('Research/research_company_content'); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="text-center"><?= __("There is no market in IPO"); ?></h3>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /MOBILE -->
</section>
<!-- / -->
<?= $this->Html->script('/js/custom') ?>