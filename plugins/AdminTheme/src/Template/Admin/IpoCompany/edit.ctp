<section id="middle">
    <header id="page-header">
        <h1><?=__('Edit Ipo Company');?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Ipo Market'), '#'); ?></li>
            <li class="active"><?=__('Edit Ipo Company');?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('EDIT IPO COMPANY');?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->Element('Form/ipo_company_form'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
