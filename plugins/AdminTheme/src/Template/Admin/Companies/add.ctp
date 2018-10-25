<section id="middle">
    <header id="page-header">
        <h1><?=__('Add Company')?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Company'), '#'); ?></li>
            <li class="active"><?=__('Add Company')?></li>
        </ol>
    </header>

    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('ADD COMPANY')?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->element('Form/company_form'); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>