<?= $this->Html->css('FrontendTheme.../css/layout-datatables'); ?>
<style>
    @media only screen and (max-width: 1024px) {
        table {
            width: 100%;
            border-collapse: collapse;
        }
    }
    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
        td:nth-of-type(1):before { content: "<?= __('Symbol') ?>"; }
        td:nth-of-type(2):before { content: "<?= __('Company Name') ?>"; }
        td:nth-of-type(3):before { content: "<?= __('Price') ?>"; }
        td:nth-of-type(4):before { content: "<?= __('Change(%)') ?>"; }
        td:nth-of-type(5):before { content: "<?= __('Change($)') ?>"; }
        td:nth-of-type(6):before { content: "<?= __('Vol.'); ?>"; }
        td:nth-of-type(7):before { content: "<?= __('Index'); ?>"; }
        td:nth-of-type(8):before { content: "<?= __('Sector'); ?>"; }
    }
    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        /*tr { border: 1px solid #ccc; }*/

        td {
            /* Behave  like a "row" */
            border: none;
            /*border-bottom: 1px solid #eee;*/
            position: relative;
            padding-left: 50%;
            text-align: right;
        }

        td:before {
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 20%;
            padding-right: 10px;
        }
    }
    @media only screen and (max-width: 350px) {
        tr{
            font-size: 11px !important;
        }
        tr .font-size{
            font-size: 11px !important;
        }
    }

    @media only screen and (max-width: 300px) {
        tr{
            font-size: 10px !important;
        }
        tr .font-size{
            font-size: 10px !important;
        }
    }

</style>

<div class="table-responsive12 table-responsive">
    <table id="company-grid"  class="table table-striped">
        <thead>
        <tr>
            <th><?= __('Symbol'); ?></th>
            <th><?= __('Company Name'); ?></th>
            <th><?= __('Price'); ?></th>
            <th><?= __('Change(%)'); ?></th>
            <th><?= __('Change($)'); ?></th>
            <th><?= __('Vol.'); ?></th>
            <th><?= __('Index'); ?></th>
            <th><?= __('Sector'); ?></th>
        </tr>
        </thead>
    </table>
</div>

<?php $this->start('scriptBottom'); ?>
    <script type="text/javascript">
        loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
            loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {
                if (jQuery().dataTable) {
                    var dataTable = $('#company-grid').DataTable({
                        "columns": [{
                            "orderable": true,
                            className: 'text-align-left'
                            }, {
                            "orderable": true,
                            className: 'text-align-left'
                            }, {
                            "orderable": false,
                            className: 'text-align-left'
                            }, {
                            "orderable": false,
                            className: 'text-align-left'
                            }, {
                            "orderable": false,
                            className: 'text-align-left'
                            }, {
                            "orderable": true,
                            className: 'text-align-left'
                            }, {
                            "orderable": true,
                            className: 'text-align-left'
                            }, {
                            "orderable": true,
                            className: 'text-align-left'
                        }],
                        "lengthMenu": [
                            [10, 15, 20],
                            [10, 15, 20] // change per page values here
                        ],
                        // set the initial value
                        "pageLength": 10,
                        "pagingType": "bootstrap_full_number",
                        "language": {
                            "lengthMenu": "  _MENU_ records",
                            "paginate": {
                                "previous": "Prev",
                                "next": "Next",
                                "last": "Last",
                                "first": "First"
                            }
                        },
                        "columnDefs": [{// set default column settings
                            'orderable': true,
                            'targets': [0]
                        }, {
                            "searchable": true,
                            "targets": [0]
                        }],
                        "order": [
                            [0, "DESC"]
                        ],
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '<?= $this->Url->build(['_name' => 'stocks_list_data_table']); ?>',
                            type: "get",
                            error: function () {
                                $(".company-grid-error").html("");
                                $("#company-grid").append('<tbody class="company-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
                                $("#company-grid_processing").css("display", "none").css("margin-left","50%");
                            }
                        }
                    });

                    $('.input-xsmall').removeClass('form-control');
                    $('.dataTables_filter').show();

                    var search_input_json_data = {};
                    $(document).on('change', '.custom-search-select-input', function () {
                        var name = $(this).attr('name');
                        var value = this.value;
                        search_input_json_data[name] = value;

                        $(".custom-search-select-input[name='"+name+"']").css("background-color", (value ? "yellow" : ""));
                        $(".custom-search-select-input[name='"+name+"']").find("option[value='"+value+"']").attr("selected","selected");

                        //calculate selected cpunt
                        var all_selected_item_count = 0;
                        $('.tab-pane').each(function(){
                            if($(this).attr('id') != 'all') {
                                var selected_item_count = 0;
                                $(this).find('.custom-search-select-input').each(function() {
                                    if($(this).val()){
                                        selected_item_count++;
                                        all_selected_item_count++;
                                    }
                                });
                                $("a.nav-link[href='#"+$(this).attr('id')+"']").find('.selected-items-count').text((selected_item_count == 0 ? "" : "("+selected_item_count+")"));
                            }else{
                                $('.selected-items-count-for-reset').text((all_selected_item_count == 0 ? "" : "("+all_selected_item_count+")"));
                                $("a.nav-link[href='#all']").find('.selected-items-count').text((all_selected_item_count == 0 ? "" : "("+all_selected_item_count+")"));
                            }
                        });

                        var query = '';
                        if (value) {
                            query = JSON.stringify(search_input_json_data);
                        }

                        dataTable.search(query);
                        dataTable.draw();
                    });

                    $("select[name='order_field'],select[name='order_by']").on('change',function(){
                        var order_field = $("select[name='order_field']").val();
                        var order_by = $("select[name='order_by']").val();
                        search_input_json_data['order_data'] = {'order_field':order_field,'order_by':order_by};

                        var query = '';
                        if (order_field || order_by) {
                            query = JSON.stringify(search_input_json_data);
                        }

                        dataTable.search(query);
                        dataTable.draw();
                    });

                    $('.input-tickers-search-icon').on('click',function(){
                        var query = $('.input-tickers-search').val();
                        var name = $('.input-tickers-search').attr('name');
                        search_input_json_data[name] = query;
                        if (query) {
                            $query = JSON.stringify(search_input_json_data);
                        }

                        dataTable.search(query);
                        dataTable.draw();
                    });

                    $('.reset-filter').click(function(){
                        search_input_json_data = {};
                        $('.selected-items-count-for-reset').text("");
                        $('.tab-pane').each(function(){
                            $("a.nav-link[href='#"+$(this).attr('id')+"']").find('.selected-items-count').text('');
                            $(this).find('.custom-search-select-input').each(function() {
                                $(this).val('').css("background-color", "");
                            });
                        });
                        dataTable.search('');
                        dataTable.draw();
                    });

                    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                      var target = $(e.target).attr("href") // activated tab
                      if(target == '#all'){
                        var all_html = $('#descriptive').html() + $('#fundamental').html() + $('#technical').html();
                        $('#all').html(all_html);
                      }
                    });

                    $('.filter-view').click(function(){
                        $(this).toggleClass( 'btn-danger' );
                        $(this).find('.fa').toggleClass( 'fa-angle-down' );
                        $('.stock-list-filter').toggle('slow');
                    });
                }
            });
        });
    </script>
<?php $this->end(); ?>
