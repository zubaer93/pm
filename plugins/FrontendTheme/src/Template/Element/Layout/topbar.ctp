<div id="topBar">
    <div class="container">
        <!-- right -->
        <ul class="top-links list-inline float-right">
            <?php if ($this->request->session()->read('Auth.User.first_name')) : ?>
                <li class="text-welcome hidden-xs-down"><?= __('Welcome to StockGitter') ?>, <strong><?= $this->request->session()->read('Auth.User.first_name') . " " . $this->request->session()->read('Auth.User.last_name') ?></strong></li> 
                <li>
                    <div class="fa-hover">
                        <a href="#"><i class="fa fa-bell-o <?= ($count_unread_notifications) ? "color_red" : "" ?>" aria-hidden="true"></i>
                            <sup class="<?= ($count_unread_notifications) ? "color_red" : "" ?> count_notif"><?= $count_unread_notifications ?></sup>
                        </a>
                    </div>
                    <ul class="dropdown-menu float-right ul_notifications notifications notification-ul" style="max-height: 150px;overflow-y: scroll;">
                        <?php foreach ($notifications as $notification): ?>
                            <li class="<?= ($notification->seen) ? 'read' : 'unread' ?>">
                                <a data-id="<?= $notification->id ?>" href="<?= $notification->url ?>"><?= $notification->title ?></a>
                            </li>
                            <li class="divider"></li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li>
                    <a class="dropdown-toggle no-text-underline" data-toggle="dropdown" href="#">
                        <i class="fa fa-user hidden-xs-down"></i> <?= __('MY ACCOUNT'); ?>
                    </a>
                    <ul class="dropdown-menu float-right">
                        <li>
                            <?=
                            $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'fa fa-users']) . __('Profile'), [
                                '_name' => 'profile'
                                    ], [
                                'tabindex' => '-1',
                                'escape' => false
                                    ]
                            );
                            ?>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?=
                            $this->Html->link(
                                    $this->Html->tag('i', '', ['class' => 'glyphicon glyphicon-off']) . __('Logout'), [
                                '_name' => 'logout'
                                    ], [
                                'tabindex' => '-1',
                                'escape' => false
                                    ]
                            );
                            ?>
                        </li>
                    </ul>
                </li>
            <?php else : ?>
                <div class="mt-p-3">  
                    <?=
                    $this->Html->link(
                            $this->Html->tag('i', '', ['class' => 'fa fa-users']) . __('Login'), [
                        '_name' => 'login'
                            ], [
                        'class' => 'btn btn-primary btn-sm ',
                        'tabindex' => '-1',
                        'escape' => false
                            ]
                    );
                    ?>
                    <?=
                    $this->Html->link(
                            $this->Html->tag('i', '', ['class' => 'fa fa-power-off']) . __('Register'), [
                        '_name' => 'register'
                            ], [
                        'class' => 'btn btn-primary btn-sm ',
                        'tabindex' => '-1',
                        'escape' => false
                            ]
                    );
                    ?>
                </div>
            <?php endif; ?>
        </ul>

        <!-- left -->
        <ul class="top-links list-inline float-left">
            <div class="custom-markets">
                <?php
                $timeForDatabase = (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern');
                $date = new \DateTime();
                $day = date("D", strtotime($timeForDatabase));
                
                if ($currentLanguage == 'USD') :
                    echo $this->Html->image('FrontendTheme._smarty/flags/us.png', ['width' => '16', 'height' => '11', 'lang' => 'lang']);
                    ?>
                    US Market
                    <?php
                    $date->setTime(4, 00);
                    $pre_begin = $date->getTimestamp();
                    $date->setTime(9, 29);
                    $pre_end = $date->getTimestamp();
                    $date->setTime(9, 30);
                    $open_begin = $date->getTimestamp();
                    $date->setTime(16, 00);
                    $open_end = $date->getTimestamp();
                    $date->setTime(16, 01);
                    $after_begin = $date->getTimestamp();
                    $date->setTime(20, 00);
                    $after_end = $date->getTimestamp();
                    if ($day == 'Sat' || $day == 'Sun'):
                        ?><span class="badge badge-danger">closed</span> <?php
                    else:
                        if (strtotime($timeForDatabase) >= $pre_begin && strtotime($timeForDatabase) <= $pre_end):
                            ?><span class="badge badge-info">premarket</span> <?php
                        elseif (strtotime($timeForDatabase) >= $open_begin && strtotime($timeForDatabase) <= $open_end):
                            ?><span class="badge badge-success">open</span> <?php
                        elseif (strtotime($timeForDatabase) >= $after_begin && strtotime($timeForDatabase) <= $after_end):
                            ?><span class="badge badge-warning">after hours</span> <?php
                        else:
                            ?><span class="badge badge-danger">closed</span> <?php
                        endif;
                    endif;
                    echo date('g:i A', strtotime($timeForDatabase));
                elseif ($currentLanguage == 'JMD') :
                    echo $this->Html->image('FrontendTheme._smarty/flags/jm.png', ['width' => '16', 'height' => '11', 'lang' => 'lang']);
                    ?>
                    JAM Market
                    <?php
                    $timeForDatabase = $timeForDatabase->setTimezone('America/Jamaica');
                    $date->setTime(9, 00);
                    $open_begin = $date->getTimestamp();
                    $date->setTime(13, 00);
                    $open_end = $date->getTimestamp();
                    if (strtotime($timeForDatabase) >= $open_begin && strtotime($timeForDatabase) <= $open_end):
                        ?><span class="badge badge-success">open</span> <?php
                    else:
                        ?><span class="badge badge-danger">closed</span> <?php
                    endif;
                    echo date('g:i A', strtotime($timeForDatabase));
                endif;
                ?>
            </div>
        </ul>

    </div>
</div>