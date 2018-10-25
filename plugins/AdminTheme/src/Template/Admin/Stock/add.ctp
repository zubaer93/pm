<!--
'required' => true is temporary solution, will change with next commit(after going live)
-->
<section id="middle">
    <header id="page-header">
        <h1><?= __('Add Company Stock'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Company'), '#'); ?></li>
            <li class="active"><?= __('Add Company Stock'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20 ajax-data" data-stock-info-url="<?= $this->Url->build(['_name' => 'stock_info']); ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?= __('ADD COMPANY STOCK'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <?= $this->Form->create('saveCompanyStock');
                        ?>
                        <fieldset>
                            <?=
                            $this->Form->control('Information *', ['type' => 'hidden',
                                'value' => 'contact_send',
                                'name' => 'action'
                            ]);
                            ?>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <label><?= __('Company *'); ?></label>
                                        <select name="company" class="form-control select2 slelect_company">
                                            <option value="" disabled selected><?= __('Select Company'); ?></option>
                                            <?php foreach ($all_companies as $company): ?>
                                                <option symbol="<?= $company['symbol'] ?>" value="<?= $company['id'] ?>"><?= $company['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Information *'), ['required' => true, 'type' => 'text',
                                            'name' => 'information',
                                            'class' => 'form-control required information'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Symbol *'), ['required' => true, 'readonly' => 'true', 'type' => 'text',
                                            'name' => 'symbol',
                                            'class' => 'form-control required symbol'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Intervals *'), ['required' => true, 'type' => 'text',
                                            'name' => 'intervals',
                                            'class' => 'form-control required intervals'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Output size *'), ['required' => true, 'type' => 'text',
                                            'name' => 'output_size',
                                            'class' => 'form-control required output_size'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Timezone series *'), ['required' => true, 'type' => 'text',
                                            'name' => 'time_series_date_time',
                                            'class' => 'form-control masked time_series_date_time',
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
                                        $this->Form->control(__('Open *'), ['required' => true, 'type' => 'text',
                                            'name' => 'open',
                                            'class' => 'form-control required open'
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?=
                                        $this->Form->control(__('High *'), ['required' => true, 'type' => 'text',
                                            'name' => 'high',
                                            'class' => 'form-control required high'
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?=
                                        $this->Form->control(__('Low *'), ['required' => true, 'type' => 'text',
                                            'name' => 'low',
                                            'class' => 'form-control required low'
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?=
                                        $this->Form->control(__('Close *'), ['required' => true, 'type' => 'text',
                                            'name' => 'close',
                                            'class' => 'form-control required close_stock'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?=
                                        $this->Form->control(__('Volume *'), ['required' => true, 'type' => 'text',
                                            'name' => 'volume',
                                            'class' => 'form-control required volume'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12">
                                        <?php
                                        $options = array(
                                         '15.44' => '138SL',
                                         '4.16' => '1834',
                                         '33.34' => 'BIL',
                                         '111.59' => 'BRG',
                                         '72.71' => 'CAR',
                                         '45.86' => 'CBNY',
                                         '120' => 'CCC',
                                         '413.73' => 'GK',
                                         '170.09' => 'GBK',
                                         '196.66' => 'JMMBGL',
                                         '58.98' => 'JP',
                                         '31.47' => 'JSE',
                                         '24.58' => 'KPREIT',
                                         '113.87' => 'KW',
                                         '58.78' => 'MIL',
                                         '816.07' => 'NCBFG',
                                         '3463.19' => 'PAL',
                                         '387.5' => 'PJAM',
                                         '78.45' => 'PJX',
                                         '183.83' => 'PROVENJA',
                                         '18.32' => 'PULS',
                                         '-0.86' => 'RJR',
                                         '75.59' => 'SALF',
                                         '154.01' => 'SEP',
                                         '430.82' => 'SGJ',
                                         '87.58' => 'SIL',
                                         '309.04' => 'SJ',
                                         '45.39' => 'SVL',
                                         '23.09' => 'VMIL',
                                         '62.56' => 'WISYNCO',
                                         '122.25' => 'XFUND',
                                         '0.46' => 'MTL',
                                         '1.47' => 'Proven',
                                         '251.03' => 'AFS',
                                         '14.5' => 'AMG',
                                         '169.88' => 'BPOW',
                                         '77.73' => 'CAC',
                                         '91.75' => 'CFF',
                                         '32.51' => 'CHL',
                                         '34.8' => 'CPJ',
                                         '112.51' => 'DCOVE',
                                         '103.1' => 'DTL',
                                         '23.82' => 'ECL',
                                         '11.9' => 'ELITE',
                                         '35.97' => 'EPLY',
                                         '10.94' => 'FOSRICH',
                                         '21.45' => 'GENAC',
                                         '12.71' => 'GWEST',
                                         '17.72' => 'HONBUN',
                                         '46.08' => 'IPS',
                                         '29.42' => 'JAMT',
                                         '26.36' => 'JETCON',
                                         '35.77' => 'KEX',
                                         '13.32' => 'KEY',
                                         '8.96' => 'KLE',
                                         '28.3' => 'KREMI',
                                         '20.84' => 'LASD',
                                         '19.54' => 'LASF',
                                         '13.19' => 'LASM',
                                         '36.62' => 'MEEG',
                                         '36.95' => 'MDC',
                                         '-9.49' => 'MUSIC',
                                         '6.94' => 'PTL',
                                         '-18' => 'PURITY',
                                         '-11.79' => 'ROC',
                                         '33.17' => 'SOS',
                                         '1.5' => 'SRA',
                                         '17.59' => 'tTech'
                                        );
                                       echo  $this->Form->control(__('Eps *'), ['required' => true, 'type' => 'select',
                                            'name' => 'EPS',
                                            'class' => 'form-control required EPS',
                                            'options'=>$options
                                        ]);
                                       /* $countries_list = [
                                            'US' => 'United State',
                                            'CA' => 'Canada',
                                            'CN' => 'China',
                                            //...etc
                                        ];

                                        //$this->set('countries', $countries_list); 
                                        //$this->Form->select('country', $countries); 
                                        $this->Form->input('Country.id', array('type'=>'select', 'label'=>'Countries', 'options'=>$countries_list, 'default'=>'3'));*/
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="row">
                            <div class="col-md-12">
                                <?=
                                $this->Form->button(__('SAVE COMPANY STOCK'), ['type' => 'submit',
                                    'class' => 'btn btn-3d btn-teal btn-xlg btn-block margin-top-30'
                                ]);
                                ?>
                            </div>
                        </div>

                        <?= $this->Form->end(); ?>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong><?= __('IMPORT COMPANY STOCK'); ?></strong>
                    </div>
                    <div class="panel-body">
                        <div id="content" class="padding-20">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-body">
                                    <?= $this->Form->create(null, ['url' => $this->Url->build(['_name' => 'import_company']), 'type' => 'file']); ?>
                                    <div class="fancy-file-upload fancy-file-default">
                                        <i class="fa fa-upload"></i>
                                        <input type="file" class="form-control" required="true" name="file" onchange="jQuery(this).next('input').val(this.value);"/>
                                        <input type="text" class="form-control" placeholder="<?= __('no file selected'); ?>" readonly=""/>
                                        <span class="button">Choose File</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?=
                                            $this->Form->button(__('IMPORT STOCK'), ['type' => 'submit',
                                                'class' => 'btn btn-primary'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</section>
<script>
    var getStockInfoUrl = "<?= $this->Url->build(['_name' => 'stock_info']); ?>";
    $(".slelect_company").change(function () {
        var option = $('option:selected', this).attr('symbol');
        var id = $('option:selected', this).val();
        $('.symbol').val(option);
        $.ajax({
            type: "GET",
            url: getStockInfoUrl,
            data: {
                id: id,
                symbol:option
            },
            success: function (response) {
                console.log(response.data);
                if (response.data) {
                    $('.high').val(response.data.high);
                    $('.information').val(response.data.information);
                    $('.intervals').val(response.data.intervals);
                    $('.low').val(response.data.low);
                    $('.open').val(response.data.open);
                    $('.close_stock').val(response.data.close);
                    $('.output_size').val(response.data.output_size);
                    $('.time_series_date_time').val(response.data.symbol);
                } else {
                    $('.high').val('');
                    $('.information').val('');
                    $('.intervals').val('');
                    $('.low').val('');
                    $('.open').val('');
                    $('.close_stock').val('');
                    $('.output_size').val('');
                    $('.time_series_date_time').val('');
                }
            },
            error: function (e) {
            }
        });

    });
</script>