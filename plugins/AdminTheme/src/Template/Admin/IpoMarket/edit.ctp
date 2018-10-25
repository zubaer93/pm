<section id="middle">
    <header id="page-header">
        <h1><?=__('Edit Ipo Market');?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Ipo Market'), '#'); ?></li>
            <li class="active"><?=__('Edit Ipo Market');?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('EDIT IPO MARKET');?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->element('Form/ipo_market_form'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>