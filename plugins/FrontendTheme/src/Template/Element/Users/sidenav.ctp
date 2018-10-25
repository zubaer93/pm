    <div class="col-lg-1"></div>
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="thumbnail text-center">
            <?= $this->Html->image($user->avatarPath, ['width' => '460', 'height' => '460', 'class' => 'img-fluid']);?>
            <h2 class="fs-18 mt-10 mb-0">
                <?= $this->Html->tag('span', $user->fullName, ['class' => 'full_name'])?>
            </h2>
        </div>
        <!-- SIDE NAV -->
        <ul class="side-nav list-group mb-30" id="sidebar-nav">
            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-comments-o']) . __('NEWS FEED'), [
                    '_name' => 'profile'
                ], [
                    'escape' => false
                ]);?>
            </li>
            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-comments-o']) . __('PRIVATE POST'), [
                    '_name' => 'private_post'
                ], [
                    'escape' => false
                ]);?>
            </li>
            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-gears']) . __('SIMULATION'), [
                    '_name' => 'simulation'
                ], [
                    'escape' => false
                ]);?>
            </li>
            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-gears']) . __('SETTINGS'), [
                    '_name' => 'settings'
                ], [
                    'tabindex' => '-1',
                    'escape' => false
                ]);?>
            </li>

            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bell']) . __('ALERTS'), [
                    '_name' => 'alerts'
                ], [
                    'tabindex' => '-1',
                    'escape' => false
                ]);?>
            </li>

            <li class="list-group-item">
                <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-credit-card']) . __('SUBSCRIPTION'), [
                    '_name' => 'subscribe'
                ], [
                    'tabindex' => '-1',
                    'escape' => false
                ]);?>
            </li>
        </ul>
        <!-- /SIDE NAV -->
    </div>