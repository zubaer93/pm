<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container-fluid ticker-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mb-20">
                <div class=" text-center mt-10 mb-20">
                    <h3><?= __('All ' . $currentLanguage . ' Stocks'); ?></h3>
                </div>
                <div class="row">

                    <div class="col-md-12 col-sm-12 stock-list-order-filter">
                        <?= $this->Form->select('type', ['0'=>'My Presets','1'=>'_123'], ['empty' => '']); ?>
                        Order: <?= $this->Form->select('order_field', ['exchange'=>'Exchange','sector'=>'Sector','industry'=>'Industry','country'=>'Country'], ['empty' => false]); ?>
                        <?= $this->Form->select('order_by', ['asc'=>'Asc', 'desc'=>'Desc'], ['empty' => false]); ?>
                        Signal: <?= $this->Form->select('None', [], ['empty' => 'None(all stocks)']); ?>
                        Tickers
                        <input type="text" name="query" class="input-tickers-search" placeholder="" aria-controls="company-grid">
                        <button type="button"><i class="fa fa-search input-tickers-search-icon"></i></button>
                        <button type="button" class="btn btn-success filter-view">Filter <i class="fa fa-angle-up"></i></button>
                    </div>

                    <div class="col-md-12 col-sm-12 stock-list-filter mt-20">

                        <div class="navbar navbar-light bg-fadedd">
                            <ul class="nav nav-tabs">
                                <a class="nav-item nav-link active" data-toggle="tab" href="#descriptive">Descriptive <span class='selected-items-count'></span></a>
                                <a class="nav-item nav-link" data-toggle="tab" href="#fundamental">Fundamental <span class='selected-items-count'></span></a>
                                <a class="nav-item nav-link" data-toggle="tab" href="#technical">Technical <span class='selected-items-count'></span></a>
                                <a class="nav-item nav-link" data-toggle="tab" href="#all">All <span class='selected-items-count'></span></a>
                            </ul>
                        </div>

                        <div class="tab-content  bg-fadedd">
                            <div class="tab-pane active" id="descriptive">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('exchange', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Exchange'), 'options' => $exchanges]); ?>
                                        <?= $this->Form->input('market_cap', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Market Cap'), 'options' => \App\Model\Service\Core::$market_caps_filter_data]); ?>
                                        <?= $this->Form->input('earnings_date', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Earnings Date'), 'options' => []]); ?>
                                        <?= $this->Form->input('target_price', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Target Price'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('index', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Index'), 'options' => $indexes]); ?>
                                        <?= $this->Form->input('dividend_yield', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Dividend Yield'), 'options' => \App\Model\Service\Core::$dividend_yield_filter_data]); ?>
                                        <?= $this->Form->input('average_volume', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Average Volume'), 'options' => []]); ?>
                                        <?= $this->Form->input('ipo_date', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('IPO Date'), 'options' => App\Model\Service\Core::$ipo_date_filter_data]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('sector', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Sector'), 'options' => $sectors]); ?>
                                        <?= $this->Form->input('float_short', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Float Short'), 'options' => []]); ?>
                                        <?= $this->Form->input('relative_volume', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Relative Volume'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('industry', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Industry'), 'options' => $industries]); ?>
                                        <?= $this->Form->input('analyst_recom.', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Analyst Recom.'), 'options' => []]); ?>
                                        <?= $this->Form->input('current_volume', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Current Volume'), 'options' => \App\Model\Service\Core::$current_volume_filter_data]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('country', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Country'), 'options' => $countries]); ?>
                                        <?= $this->Form->input('pption_short', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Option/Short'), 'options' => []]); ?>
                                        <?= $this->Form->input('price', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Price'), 'options' => \App\Model\Service\Core::$price_filter_data]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="fundamental">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('p_e', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('P/E::'), 'options' => []]); ?>
                                        <?= $this->Form->input('price_cash', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Price/Cash'), 'options' => []]); ?>
                                        <?= $this->Form->input('eps_growth_next_5_year', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('EPS growth next 5 years'), 'options' => []]); ?>
                                        <?= $this->Form->input('return_on_equity', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Return on Equity'), 'options' => []]); ?>
                                        <?= $this->Form->input('debt_equity', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Debt/Equity'), 'options' => []]); ?>
                                        <?= $this->Form->input('price', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Price'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('forward_p_e', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Forward P/E'), 'options' => []]); ?>
                                        <?= $this->Form->input('price_free_cash_flow', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Price/Free Cash Flow'), 'options' => []]); ?>
                                        <?= $this->Form->input('sales_growth_past_5_years', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Sales growth past 5 years'), 'options' => []]); ?>
                                        <?= $this->Form->input('return_on_investment', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Return on Investment'), 'options' => []]); ?>
                                        <?= $this->Form->input('gross_margin', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Gross Margin'), 'options' => []]); ?>
                                        <?= $this->Form->input('insider_ownership', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Insider Ownership'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('peg', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('PEG'), 'options' => []]); ?>
                                        <?= $this->Form->input('eps_growth_this_year', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('EPS growth this year'), 'options' => []]); ?>
                                        <?= $this->Form->input('eps_growth_qtr_over_qtr', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('EPS growth qtr over qtr'), 'options' => []]); ?>
                                        <?= $this->Form->input('current_ratio', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Current Ratio'), 'options' => []]); ?>
                                        <?= $this->Form->input('operating_margin', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Operating Margin'), 'options' => []]); ?>
                                        <?= $this->Form->input('insider_transactions', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Insider Transactions'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('p_s', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('P/S'), 'options' => []]); ?>
                                        <?= $this->Form->input('eps_growth_next_year.', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('EPS growth next year.'), 'options' => []]); ?>
                                        <?= $this->Form->input('sales_growth_qtr_over_qtr', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Sales growth qtr over qtr'), 'options' => []]); ?>
                                        <?= $this->Form->input('quick_ratio', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Quick Ratio'), 'options' => []]); ?>
                                        <?= $this->Form->input('net_profit_margin', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Net Profit Margin'), 'options' => []]); ?>
                                        <?= $this->Form->input('institutional_ownership', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Institutional Ownership'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('p_b', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('P/B'), 'options' => []]); ?>
                                        <?= $this->Form->input('eps_growth_past_5_year', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('EPS growth past 5 years'), 'options' => []]); ?>
                                        <?= $this->Form->input('return_on_assets', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Return on Assets'), 'options' => []]); ?>
                                        <?= $this->Form->input('lt_debt_equity', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('LT Debt/Equity'), 'options' => []]); ?>
                                        <?= $this->Form->input('payout_ratio', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Payout Ratio'), 'options' => []]); ?>
                                        <?= $this->Form->input('institutional_transactions', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Institutional Transactions'), 'options' => []]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="technical">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('performance', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Performance'), 'options' => []]); ?>
                                        <?= $this->Form->input('20_day_simple_moving_average', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('20-Day Simple Moving Average'), 'options' => []]); ?>
                                        <?= $this->Form->input('20_day_high_low', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('20-Day High/Low'), 'options' => []]); ?>
                                        <?= $this->Form->input('beta', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Beta'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('performance_2', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Performance 2'), 'options' => []]); ?>
                                        <?= $this->Form->input('50_day_simple_moving_average', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('50-Day Simple Moving Average'), 'options' => []]); ?>
                                        <?= $this->Form->input('50_day_high_low', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('50-Day High/Low'), 'options' => []]); ?>
                                        <?= $this->Form->input('average_true_range', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Average True Range'), 'options' => []]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('volatility', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Volatility'), 'options' => []]); ?>
                                        <?= $this->Form->input('200_day_simple_moving_average', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('200-Day Simple Moving Average'), 'options' => []]); ?>
                                        <?= $this->Form->input('week_52_high', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('52 Week High'), 'options' => \App\Model\Service\Core::$high_low_price_52_week_filter_data]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('rsi_14', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('RSI (14)'), 'options' => []]); ?>
                                        <?= $this->Form->input('change', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Change'), 'options' => App\Model\Service\Core::$change_filter_data]); ?>
                                        <?= $this->Form->input('week_52_low', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('52 Week Low'), 'options' => \App\Model\Service\Core::$high_low_price_52_week_filter_data]); ?>
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <?= $this->Form->input('gap', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Gap'), 'options' => []]); ?>
                                        <?= $this->Form->input('change_from_open', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Change from Open'), 'options' => []]); ?>
                                        <?= $this->Form->input('pattern', ['class' => 'custom-search-select-input mb-10', 'type' => 'select', 'empty' => 'Any', 'label' => __('Pattern'), 'options' => []]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="all">


                            </div>
                            <button type="button" class="reset-filter btn btn-danger btn-sm">Reset Filter <span class="selected-items-count-for-reset"></span></button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 mb-80">
                <div class="box-light">
                    <div class="box-inner">
                        <?= $this->element('Symbol/dataTable/stocks'); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <?= $this->element('Event/calendar')?>
            </div>
        </div>
    </div>
</section>

