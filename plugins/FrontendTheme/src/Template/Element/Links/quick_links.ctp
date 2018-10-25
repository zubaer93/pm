<?php if ($authUser): ?>
    <div class="dropdownlist">
        <button class="btn mr-1 dropdown-toggle dropbtn"><?= __('Quick links'); ?></button>
        <div class="dropdown-content">
            <?=
            $this->Html->link(__('Your Portfolio'), ['_name' => 'all-simulations'], ['tabindex' => '-1', 'class' => 'btn-link',
                'escape' => false
            ]);
            ?>
            <?=
            $this->Html->link(__('Order History'), ['_name' => 'order'], ['tabindex' => '-1', 'class' => 'btn-link',
                'escape' => false
            ]);
            ?>
            <?php if ($subdomain): ?>
                <?=
                $this->Html->link(__('All Watchlist'), ['_name' => 'watchlist_all'], ['tabindex' => '-1', 'class' => 'btn-link',
                    'escape' => false
                ]);
                ?>
                <?=
                $this->Html->link(__('All Analysis'), ['controller' => 'Analysis', 'action' => 'all'], ['tabindex' => '-1', 'class' => 'btn-link',
                    'escape' => false
                ]);
                ?>
                <?=
                $this->Html->link(__('Simulation'), ['_name' => 'all-simulations'], ['tabindex' => '-1', 'class' => 'btn-link',
                    'escape' => false
                ]);
                ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>