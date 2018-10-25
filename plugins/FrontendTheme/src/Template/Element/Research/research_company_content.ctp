<!-- research company content -->
<div class="tab-pane active" id="home">
    <p>
        <?php if ($data['currentCompany']): ?>
            <?= $data['currentCompany']->about; ?>
        <?php else: ?>
        <h3 class="text-center"><?= __("There is no company in this market"); ?></h3>
    <?php endif; ?>
</p>
</div>
<!-- /research company content -->