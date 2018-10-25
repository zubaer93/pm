<!-- ipo market -->
<?php foreach ($data['allMarkets'] as $market): ?>
    <?php if (!is_null($data['currentMarket']) && $data['currentMarket']->name === $market->name): ?>
        <li class="active nav-item">
    <?php else: ?>
        <li class="nav-item">
    <?php endif; ?>
            <?= $this->Html->link(__($market->name), [
                '_name' => 'ipo-market',
                $market->slug
            ], ['class' => 'nav-link']);
            ?>
        </li>
<?php endforeach; ?>
<!-- /ipo market -->