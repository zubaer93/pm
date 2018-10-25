<section id="middle">
    <header id="page-header">
        <h1><?=__('Edit Research Company');?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Research Market'), '#'); ?></li>
            <li class="active"><?=__('Edit Research Company');?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('EDIT RESEARCH COMPANY');?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->Element('Form/research_company_form'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
