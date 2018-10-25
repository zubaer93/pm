<section id="middle">

    <header id="page-header">
        <h1><?= __('Edit Stock Details'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Stock'), ['_name' => 'stock_details']); ?></li>
            <li class="active"><?= __('Edit Stock Details'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit Stock Details'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('stock', ['enctype' => 'multipart/form-data']); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id', ['value' => $stock->id]); ?>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3">
                                    <label><?= __('Company Symbol'); ?></label>
                                    <i class=""></i>
                                    <?= $this->Form->select('company_id', $all_companies, ['default' => $stock->company_id, 'id' => 'dates-field2', 'class' => 'form-control select2',]); ?>                  
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('High price 52 week *'), ['required' => true, 'value' => $stock->high_price_52_week, 'type' => 'text',
                                        'name' => 'high_price_52_week',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('High price 52 ind *'), ['required' => true, 'value' => $stock->high_price_52_ind,'type' => 'text',
                                        'name' => 'high_price_52_ind',
                                        'class' => 'form-control required symbol'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Low price 52 week *'), ['required' => true, 'value' => $stock->low_price_52_week, 'type' => 'text',
                                        'name' => 'low_price_52_week',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Low price 52 ind *'), ['required' => true, 'value' => $stock->low_price_52_ind, 'type' => 'text',
                                        'name' => 'low_price_52_ind',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Days high price *'), ['required' => true, 'value' => $stock->days_high_price, 'type' => 'text',
                                        'name' => 'days_high_price',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Days low price *'), ['required' => true, 'value' => $stock->days_low_price, 'type' => 'text',
                                        'name' => 'days_low_price',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Close price *'), ['required' => true, 'value' => $stock->close_price, 'type' => 'text',
                                        'name' => 'close_price',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Close net change *'), ['required' => true, 'value' => $stock->close_net_change, 'type' => 'text',
                                        'name' => 'close_net_change',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Close percent change *'), ['required' => true, 'value' => $stock->close_percent_change, 'type' => 'text',
                                        'name' => 'close_percent_change',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Last traded price *'), ['required' => true, 'value' => $stock->last_traded_price, 'type' => 'text',
                                        'name' => 'last_traded_price',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Bid price *'), ['required' => true, 'value' => $stock->bid_price, 'type' => 'text',
                                        'name' => 'bid_price',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Ask price *'), ['required' => true, 'value' => $stock->ask_price, 'type' => 'text',
                                        'name' => 'ask_price',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Total traded volume *'), ['required' => true, 'value' => $stock->total_traded_volume, 'type' => 'text',
                                        'name' => 'total_traded_volume',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Trade value *'), ['required' => true, 'value' => $stock->trade_value, 'type' => 'text',
                                        'name' => 'trade_value',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Num of trades *'), ['required' => true, 'value' => $stock->num_of_trades, 'type' => 'text',
                                        'name' => 'num_of_trades',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Market cap *'), ['required' => true, 'value' => $stock->market_cap, 'type' => 'text',
                                        'name' => 'market_cap',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Total issued shares *'), ['required' => true, 'value' => $stock->totalissuedshares, 'type' => 'text',
                                        'name' => 'totalissuedshares',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Pre dividend amount *'), ['required' => true, 'value' => $stock->pre_dividend_amount, 'type' => 'text',
                                        'name' => 'pre_dividend_amount',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Pre div curr *'), ['required' => true, 'value' => $stock->pre_div_curr, 'type' => 'text',
                                        'name' => 'pre_div_curr',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Dividend amount *'), ['required' => true, 'value' => $stock->dividend_amount, 'type' => 'text',
                                        'name' => 'dividend_amount',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Div curr *'), ['required' => true, 'value' => $stock->div_curr, 'type' => 'text',
                                        'name' => 'div_curr',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <?= $this->Form->button('<i class="fa fa-check"></i>' . __('SAVE'), ['class' => 'btn btn-primary btn-lg btn-block']); ?>

                            </div>
                        </div>
                    </fieldset>
                    <?= $this->Form->end(); ?>
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

</section>