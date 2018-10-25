                <?php $this->Html->scriptStart(['block' => true]); ?>
                loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
                loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {
if (jQuery().dataTable) {
                                var dataTable = $('#time-and-sales-grid').DataTable({
                    "columns": [{
                            "orderable": true
                        }, {
                            "orderable": true
                        }, {
                            "orderable": true
                        }, {
                            "orderable": true
                        }],
                    "lengthMenu": [
                        [1,5, 10, 20],
                        [1,5, 10, 20] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 1,
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
                        [2, "DESC"]
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: '<?= $this->Url->build(['_name' => 'data_table_time_and_sales']); ?>',
                        type: "get",
                        data:{
                        'symbol':"<?= $symbol; ?>"
                        },
                        error: function () {
                            $(".time-and-sales-grid-error").html("");
                            $("#time-and-sales-grid").append('<tbody class="time-and-sales-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#time-and-sales-grid_processing").css("display", "none");
                        }
                    }
                });
                }
                      });
                });
                <?php $this->Html->scriptEnd(); ?>