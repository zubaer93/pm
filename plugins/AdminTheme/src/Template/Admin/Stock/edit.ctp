<section id="middle">

    <header id="page-header">
        <h1><?= __('Edit Stock'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Stock'), ['_name' => 'news_list']); ?></li>
            <li class="active"><?= __('Edit Stock'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit Stock'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('stock', ['enctype' => 'multipart/form-data']); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id', ['value' => $stock->id]); ?>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12">
                                    <?=
                                    $this->Form->control(__('Company'), ['value' => $company->name, 'type' => 'text', 'readonly' => 'true',
                                        'name' => 'information',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12">
                                    <?=
                                    $this->Form->control(__('Information *'), ['required' => true, 'value' => $stock->information, 'type' => 'text',
                                        'name' => 'information',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6">
                                    <?=
                                    $this->Form->control(__('Symbol *'), ['required' => true, 'value' => $stock->symbol, 'readonly' => 'true', 'type' => 'text',
                                        'name' => 'symbol',
                                        'class' => 'form-control required symbol'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?=
                                    $this->Form->control(__('Intervals *'), ['required' => true, 'value' => $stock->intervals, 'type' => 'text',
                                        'name' => 'intervals',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6">
                                    <?=
                                    $this->Form->control(__('Output size *'), ['required' => true, 'value' => $stock->output_size, 'type' => 'text',
                                        'name' => 'output_size',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?=
                                    $this->Form->control(__('Timezone series *'), ['required' => true, 'value' => date('d/m/Y H:i:s', strtotime($stock->time_series_date_time)), 'type' => 'text',
                                        'name' => 'time_series_date_time',
                                        'class' => 'form-control masked',
                                        'data-format' => '99/99/9999 99:99:99',
                                        'data-placeholder' => '_',
                                        'placeholder' => 'DD/MM/YYYY 00:00:00'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Open *'), ['required' => true, 'value' => $stock->open, 'type' => 'text',
                                        'name' => 'open',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('High *'), ['required' => true, 'value' => $stock->high, 'type' => 'text',
                                        'name' => 'high',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Low *'), ['required' => true, 'value' => $stock->low, 'type' => 'text',
                                        'name' => 'low',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <?=
                                    $this->Form->control(__('Close *'), ['required' => true, 'value' => $stock->close, 'type' => 'text',
                                        'name' => 'close',
                                        'class' => 'form-control required'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12">
                                    <?=
                                    $this->Form->control(__('Volume *'), ['required' => true, 'value' => $stock->volume, 'type' => 'text',
                                        'name' => 'volume',
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