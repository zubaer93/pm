<div class="modal fade bs-example-modal-full" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <!-- header modal -->
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"><?= __('Financial Statement'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <?= $this->Html->css('FrontendTheme.../css/layout-datatables'); ?>
                <div class="table-responsive12 table-responsive">
                    <table id="financial-grid"  class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-align-center"><?= __('Title') ?></th>
                                <th class="text-align-center"><?= __('Company Name') ?></th>
                                <th class="text-align-center"><?= __('Published at') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <?php $this->Html->scriptStart(['block' => true]); ?>
                loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
                loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

                if (jQuery().dataTable) {
                var dataTable = $('#financial-grid').DataTable({
                "columns": [{
                "orderable": true
                }, {
                "orderable": true
                }, {
                "orderable": true
                }],
                "lengthMenu": [
                [5, 10, 20],
                [5, 10, 20] // change per page values here
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
                "columnDefs": [{
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
                url: '<?= $this->Url->build(['_name' => 'data_table_financial_statements', 'symbol' => $symbol]); ?>',
                type: "POST",

                error: function () {
                $(".financial-grid-error").html("");
                $("#financial-grid").append('<tbody class="financial-grid-error"><tr><th class="text-center" colspan="5">No data found in the server</th></tr></tbody>');
                $("#financial-grid_processing").css("display", "none");
                }
                }
                });
                }
                  
                if(<?= isset($partialJS) ?>){
                if (jQuery().dataTable) {
                                var dataTable = $('#time-and-sales-grid').DataTable({
                    "columns": [{
                            "orderable": true
                        }, {
                            "orderable": false
                        },{
                            "orderable": true
                        }, {
                            "orderable": true
                        }, {
                            "orderable": true
                        }],
                    "lengthMenu": [
                        [4,10, 20],
                        [4,10, 20] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 4,
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
                    "columnDefs": [{
                            'orderable': false,
                            'targets': [0]
                        }, {
                            "searchable": false,
                            "targets": [0]
                        }],
                    "order": [
                        [4, "DESC"]
                    ],
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                     "lengthChange": false,
                    "ajax": {
                        url: '<?= $this->Url->build(['_name' => 'data_table_time_and_sales']); ?>',
                        type: "GET",
                        data:{
                        'symbol':"<?= $symbol; ?>"
                        },
                        error: function () {
                            $(".time-and-sales-grid-error").html("");
                            $("#time-and-sales-grid").append('<tbody class="time-and-sales-grid-error"><tr><th class="text-center" colspan="5">No data found in the server</th></tr></tbody>');
                            $("#time-and-sales-grid_processing").css("display", "none");
                        }
                    }
                });
                }
                }
                
                });
                });
                <?php $this->Html->scriptEnd(); ?>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>