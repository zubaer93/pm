<div class="box-light mb-10">
    <div class="box-inner">
        <div class="box-messages">
            <h4 class="mb-20">
                <?= __('Company overview'); ?>
            </h4>
        </div>
        <?= $companyInfo['company_overview']; ?>
    </div>
</div>

<div class="box-light mb-10">
    <div class="box-inner">
        <div class="box-messages">
            <h4 class="mb-20">
                <?= __('Key People'); ?>
            </h4>
        </div>
        <div class="row">
            <?php foreach ($companyInfo->key_people as $keyPerson): ?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail">
                        <?= $this->Html->image($keyPerson->showPhoto, ['class' => 'img-fluid']);?>
                        <div class="caption">
                            <h4><?= $keyPerson->name; ?></h4>
                            <p><?= $keyPerson->title; ?></p>
                            <?php if ($keyPerson->age): ?>
                                <p class="text-center"><?= $keyPerson->age; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="box-light mb-10">
    <div class="box-inner">
        <div class="box-messages">
            <h4 class="mb-20">
                <?= __('Partners'); ?>
            </h4>
        </div>
        <?php if (!empty($companyInfo->affiliates)) :?>
            <div class="row">
                <?php foreach ($companyInfo->affiliates as $company): ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <?= $this->Html->image($company->showLogo, ['class' => 'img-fluid']);?>
                            <div class="caption">
                                <h4 class="text-center no-border"><?= $company->name; ?></h4>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h4 class="text-center no-border"><?= __('No Partners found for this Company');?></h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>