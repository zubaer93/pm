<!-- ipo company content -->
<div class="container">
    <div class="tab-pane active" id="home">
        <p>
            <?php //dd($data); ?>
            <?php if ($data['currentCompany']): ?>
            <p class="text-right">
                <?php if (!$data['isUserInterested']): ?>
                    <?=
                    $this->Html->link(__("I'm interested"), [
                        '_name' => 'ipo-interesting',
                        'companyId' => $data['currentCompany']->id
                            ], ['class' => 'btn btn-primary']);
                    ?>
                <?php else: ?>
                    <?=
                    $this->Html->link(__("I'm not interested"), [
                        '_name' => 'ipo-not-interesting',
                        'interestId' => $data['isUserInterested']
                            ], ['class' => 'btn btn-default']);
                    ?>
                <?php endif; ?>
            </p>
            <?= $data['currentCompany']->about; ?>
        <?php else: ?>
            <h3 class="text-center"><?= __("There is no company in this market"); ?></h3>
        <?php endif; ?>
        </p>
    </div>
</div>
<!-- /ipo company content -->