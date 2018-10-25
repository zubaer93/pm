<!-- ipo company -->
<?php foreach ($data['currentMarket']['ipo_company'] as $company): ?>
    <?php if (!is_null($data['currentCompany']) && $data['currentCompany']->name === $company->name): ?>
        <li class="active nav-item">
    <?php else: ?>
        <li class="nav-item">
    <?php endif; ?>
        <?php
        if (!is_null($data['currentCompany']) && $data['currentCompany']->name === $company->name) {
            echo $this->Html->link(__($company->name), [
                '_name' => 'ipo-company',
                $data['currentMarket']->slug,
                $company->slug
            ], ['class' => 'nav-link active']);
        } else {
            echo $this->Html->link(__($company->name), [
                '_name' => 'ipo-company',
                $data['currentMarket']->slug,
                $company->slug
            ], ['class' => 'nav-link']);
        }
        ?>
    </li>
<?php endforeach; ?>
<!-- /ipo company -->
