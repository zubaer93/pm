<div id="wrapper">
    <aside id="aside">
        <nav id="sideNav">
            <ul class="nav nav-list">
                <li class="active">
                    <a class="dashboard" href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-users"></i> <span><?= __('Users'); ?></span>
                    </a>
                    <ul>
                        <li class="active"><?= $this->Html->link('Users', ['_name' => 'users_list']); ?></li>  
                    </ul>                     
                </li>
                <li class="">
                    <a class="dashboard" href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-thumbs-up"></i> <span><?= __('Brokers'); ?></span>
                    </a>
                    <ul>
                        <li class=""><?= $this->Html->link('Brokers', ['_name' => 'brokers_list']); ?></li>   
                        <li class=""><?= $this->Html->link(__('Add Broker'), ['_name' => 'add_broker']); ?></li>   
                        <li class=""><?= $this->Html->link('Brokers Sell Buy', ['_name' => 'buy_sell_broker']); ?></li>   
                        <li class=""><?= $this->Html->link(__('Add Brokers Sell Buy'), ['_name' => 'add_buy_sell_broker']); ?></li>   
                    </ul>                     
                </li>
                <li class="">
                    <a class="dashboard" href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-tags"></i> <span><?= __('Partners'); ?></span>
                    </a>
                    <ul>
                        <li class=""><?= $this->Html->link('Partners', ['_name' => 'partners_list']); ?></li>   
                        <li class=""><?= $this->Html->link(__('Add Partner'), ['_name' => 'add_partner']); ?></li>   
                    </ul>                     
                </li>
                <li class="">
                    <a class="dashboard" href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-newspaper-o"></i> <span>News</span>
                    </a>
                    <ul>
                        <li class=""><?= $this->Html->link('News', ['_name' => 'news_list']); ?></li>   
                        <li class=""><?= $this->Html->link(__('Add News'), ['_name' => 'add_news']); ?></li>
                        <li><?= $this->Html->link(__('All Statements'), ['_name' => 'financial_statement']); ?></li>
                        <li><?= $this->Html->link(__('Add Statements'), ['_name' => 'add_financial_statement']); ?></li>
                    </ul>                     
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-building-o"></i> <span>Company</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Companies'), ['_name' => 'all_company']); ?></li>
                        <li><?= $this->Html->link(__('All Company Stock'), ['_name' => 'all_company_stock']); ?></li>
                        <li><?= $this->Html->link(__('All Company Stock Details'), ['_name' => 'stock_details']); ?></li>
                        <li><?= $this->Html->link(__('Add Company'), ['_name' => 'add_company']); ?></li>
                        <li><?= $this->Html->link(__('Add Company Stock'), ['_name' => 'add_stock']); ?></li>
                        <li><?= $this->Html->link(__('Add Company Stock Details'), ['_name' => 'add_stock_details']); ?></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-building-o"></i> <span>Affiliates</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Affiliates'), ['_name' => 'all_affiliates']); ?></li>
                        <li><?= $this->Html->link(__('Add Affiliates'), ['_name' => 'add_affiliate']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-pencil-square-o"></i> <span>Ipo</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Interests'), ['_name' => 'interests']); ?></li>
                        <li><?= $this->Html->link(__('All Markets'), ['_name' => 'all_ipo_markets']); ?></li>
                        <li><?= $this->Html->link(__('All Companies'), ['_name' => 'all_ipo_companies']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-search"></i> <span>Research</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Markets'), ['_name' => 'all_research_markets']); ?></li>
                        <li><?= $this->Html->link(__('All Companies'), ['_name' => 'all_research_companies']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-commenting-o"></i> <span>Posts</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Posts'), ['_name' => 'all_posts']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-list-alt"></i> <span>Pages</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Pages'), ['_name' => 'all_pages']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa-pie-chart "></i> <span>Sector performances</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Sector performances'), ['_name' => 'sector_performances']); ?></li>
                        <li><?= $this->Html->link(__('Add Sector performance'), ['_name' => 'add_sector_performance']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa fa-file-text-o"></i> <span>Order History</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Order'),['_name' => 'order_list']); ?></li>
                    </ul>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa fa-file-text-o"></i> <span>Event</span>
                    </a>
                    <ul>
                        <li><?= $this->Html->link(__('All Event'),['_name' => 'all_event']); ?></li>
                        <li><?= $this->Html->link(__('Add Event'),['_name' => 'add_event']); ?></li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-menu-arrow pull-right"></i>
                        <i class="main-icon fa fa fa-cog"></i> <span>Settings</span>
                    </a>
                    <ul>
                        <li>
                            <?= $this->Html->link(__('Manage Settings'), ['_name' => 'settings_index']); ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <span id="asidebg"></span>
    </aside>
    <header id="header">
        <button id="mobileMenuBtn"></button>

        <span class="logo pull-left">

        </span>
    </header>