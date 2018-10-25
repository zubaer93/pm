<!-- research company -->
<?php foreach ($data['currentMarket']['research_company'] as $company): ?>
   <?php if (!is_null($data['currentCompany']) && $data['currentCompany']->name === $company->name): ?>
        <li class="active nav-item">
    <?php else: ?>
        <li class="nav-item">
    <?php endif; ?>
        <?php
        if (!is_null($data['currentCompany']) && $data['currentCompany']->name === $company->name) {
            echo $this->Html->link(__($company->name), [
                '_name' => 'research-company',
                $data['currentMarket']->slug,
                $company->slug
            ], ['class' => 'nav-link active nav-tabs-active']);
        } else {
            echo $this->Html->link(__($company->name), [
                '_name' => 'research-company',
                $data['currentMarket']->slug,
                $company->slug
            ], ['class' => 'nav-link']);
        }
        ?>
    </li>
<?php endforeach; ?>
<!-- /research company -->
