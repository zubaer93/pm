<?= $this->Html->css('/assets/css/custom'); ?>
<section id="middle">
    <header id="page-header">
        <h1><?= __('All Interests'); ?></h1>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <?php if(!is_null($interests) && count($interests) > 0): ?>
                <div class="panel-body">
                    <div id="filter-heading">
                        <div class="pull-left">
                            <strong><?= __("Filter by"); ?></strong>
                            <select id="companyFilter" class="form-control select2" value="">
                                <option selected value="-1"><?= __("All Companies"); ?></option>
                                <?php foreach ($allIpoCompanies as $ipoCompany): ?>
                                    <option value=<?= $ipoCompany->id ?>><?= $ipoCompany->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="experienceFilter" class="form-control select2" value="">
                                <option selected value="-1"><?= __("All Experiences"); ?></option>
                                <?php foreach (\App\Model\Service\Core::$experience as $key => $experience): ?>
                                    <option value=<?= $key ?>><?= $experience; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pull-right">
                            <?= $this->Html->link(__("Company Stats"), [
                                    '_name' => 'ipo-company-stats'
                                ], ['class' => 'btn btn-primary']); ?>
                        </div>
                    </div>
                    <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                        <thead>
                        <tr>
                            <th><?= __('Ipo Company'); ?></th>
                            <th><?= __('First Name'); ?></th>
                            <th><?= __('Last Name'); ?></th>
                            <th><?= __('Username'); ?></th>
                            <th><?= __('Email'); ?></th>
                            <th><?= __('Active status'); ?></th>
                            <th><?= __('Experience'); ?></th>
                        </tr>
                        </thead>
                        <?= $this->Element('Interest/company_filter') ?>
                    </table>
                    <ul class="pagination justify-content-center">
                        <?= $this->Paginator->prev('« '.__('Previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('Next').' »') ?>
                    </ul>
                </div>
            <?php else: ?>
                <h3><?= __("No interested users") ?></h3>
            <?php endif ?>
        </div>
    </div>
</section>
<?= $this->Html->script('/assets/js/custom') ?>