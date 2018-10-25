<section id="middle">
    <header id="page-header">
        <h1><?=__('Add Research Market')?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Research Market'), '#'); ?></li>
            <li class="active"><?=__('Add Research Market')?></li>
        </ol>
    </header>

    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('ADD RESEARCH MARKET')?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->element('Form/research_market_form'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>