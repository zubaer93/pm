<section id="middle">
    <header id="page-header">
        <h1><?=__('Interesting Compnaies');?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('All Interests'), '#'); ?></li>
            <li class="active"><?=__('Interesting Compnaies');?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?=__('INTERESTING COMPANIES');?></strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                        <thead>
                        <tr>
                            <th><?= __('Ipo Company'); ?></th>
                            <th><?= __('Interested Users Count'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allIpoCompanies as $ipoCompany): ?>
                                <tr>
                                    <td>
                                        <?= $ipoCompany['ipo_company_name']; ?>
                                    </td>
                                    <td>
                                        <?= $ipoCompany['interesting_count']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
