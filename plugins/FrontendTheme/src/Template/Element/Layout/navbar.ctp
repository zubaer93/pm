<?php $this->Html->script(['search.js'], ['block' => 'script']);?>

<div id="header" class="navbar-toggleable-md sticky header-md clearfix">
    <!-- TOP NAV -->
    <header id="topNav">
        <div class="container">

            <!-- Mobile Menu Button -->
            <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <ul class="top-links list-inline float-right p-t-6 markets-hidden" id="topMainMobile">
                <li class="dropdown markets-dropdown">  
                    <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-dollar-sign']) . __(' Subscription'), ['_name' => 'subscribe'], [
                        'tabindex' => '-1',
                        'class' => 'color_red',
                        'escape' => false
                    ]);?>
                </li >
                <li class="dropdown markets-dropdown">  
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-exchange']) . __(' $FX'), ['_name' => 'forex'], [
                        'tabindex' => '-1',
                        'class' => 'color_orange',
                        'escape' => false
                            ]
                    );
                    ?>
                </li >
                <li class="dropdown markets-dropdown">
                  <?= $this->Html->link(
                    $this->Html->tag('i', '', ['class' => 'fa fa-dollar-sign']) . 
                    __(' Bonds'), ['_name' => 'bonds'], [
                      'tabindex' => '-1',
                      'class' => 'color_purple',
                      'escape' => false
                      ]
                  ); ?>
                </li>
                <li class="dropdown markets-dropdown">  
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' US'), '/USD/dashboard', [
                        'tabindex' => '-1',
                        'class' => 'color_blue',
                        'escape' => false
                            ]
                    );
                    ?>
                </li >
                <li class="dropdown markets-dropdown"> 
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' JMD'), '/JMD/dashboard', [
                        'tabindex' => '-1',
                        'class' => 'color_red',
                        'escape' => false
                            ]
                    );
                    ?>
                </li>
                <li class="dropdown">
                    <div class="inline-search clearfix  ml-10 mobile-search">
                        <input type="search" placeholder="<?= __('Search...'); ?>" class="serch-input serch-input-mobile" id="js-search-navbar-mobail">
                    </div>
                </li>
            </ul>

            <!-- BUTTONS -->
            <div class="navbar-collapse collapse float-right nav-main-collapse">
                <nav class="nav-main">
                    <ul id="topMain" class="nav nav-pills nav-main <?= ($this->request->session()->read('Auth.User')) ? (($sub) ? 'fs-13' : 'fs-15') : ''; ?>">
                        <li class="dropdown">  
                            <?= $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-dollar']) . __(' Subscription'), ['_name' => 'subscribe'], [
                                'tabindex' => '-1',
                                'class' => 'color_red',
                                'data-toggle' => 'modal',
                                'data-target' => '.bs-subscription-modal-full',
                                'escape' => false
                            ]);?>
                        </li>

                        <?= $this->element('Layout/fx'); ?>
                        <?= $this->element('Layout/bonds'); ?>
                        <?= $this->element('Layout/markets'); ?>
                        <!-- SEARCH -->
                        <?= $this->element('Layout/explore'); ?>

                        <li class="dropdown">
                            <div class="inline-search clearfix  ml-10">
                                <input type="search" placeholder="<?= __('Search...'); ?>" class="serch-input" id="js-search-navbar">
                                <img class='search-navbar-loader' src="<?= $this->Url->build('frontend_theme/img/loading-small.gif') ?>"
                                     alt="Loader" style="position: absolute;top: 6px;right: 29px;width: 20px;display: none;"
                                     data-lang="<?php echo $currentLanguage;?>"
                                >
                            </div>
                            <div id="companies_search" data-url="<?= $this->Url->build(['_name' => 'companies_search']); ?>">
                            </div>
                        </li>
                        <!-- /SEARCH -->

                    </ul>
                </nav>
            </div>
            <!-- /BUTTONS -->

            <!-- Logo -->
            <?=
            $this->Html->link($this->Html->image($logo_url, ['alt' => _('StockGitter Logo')]), [
                '_name' => 'home'
                    ], [
                'class' => 'logo',
                'escape' => false
            ]);
            ?>
        </div>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109016216-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-109016216-1');
        </script>
    </header>
    <!-- /Top Nav -->
</div>