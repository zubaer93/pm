<!-- research market -->
<?php foreach ($data['allMarkets'] as $market): ?>
    <?php if (!is_null($data['currentMarket']) && $data['currentMarket']->name === $market->name): ?>
        <?php $active = 'nav-tabs-active'; ?>
        <li class="active nav-item">
        <?php else: ?>
            <?php $active = ''; ?>
        <li class="nav-item">
        <?php endif; ?>
        <?=
        $this->Html->link(__($market->name), [
            '_name' => 'research-market',
            $market->slug
                ], ['class' => 'nav-link '.$active]);
        ?>
    </li>
<?php endforeach; ?>
<!-- /research market -->