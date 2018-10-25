<li class="dropdown">
    <?= $this->Html->link(__('Explore'), '#', ['class' => 'dropdown-toggle']); ?>
    <ul class="dropdown-menu">
        <?= $this->element('Layout/navbar_menu'); ?>
        <?php if ($authUser): ?>
            <li class="dropdown">
                <?=
                $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-chevron-right']) . __('Your Portfolio'), ['_name' => 'all-simulations'], ['tabindex' => '-1',
                    'escape' => false
                ]);
                ?>
            </li>
        <?php endif; ?>
    </ul>
</li>