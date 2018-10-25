<?= $this->Html->css('FrontendTheme.../css/layout-datatables'); ?>
<div class="table-responsive12 table-responsive">
    <table id="news-grid"  class="table table-striped">
        <thead>
            <tr>
                <th class="text-align-center"><?= __('Img') ?> </th>

                <th class="text-align-center"><?= __('Title') ?></th>
                <th class="text-align-center"><?= __('Published at') ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php $this->Html->scriptStart(['block' => true]); ?>
loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
        loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

            if (jQuery().dataTable) {
                var dataTable = $('#news-grid').DataTable({
                    "columns": [{
                            "orderable": false
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
                        [2, "DESC"]
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: '<?= $this->Url->build(['_name' => 'data_table_news_front']); ?>',
                        type: "POST",

                        error: function () {
                            $(".news-grid-error").html("");
                            $("#news-grid").append('<tbody class="news-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#news-grid_processing").css("display", "none");
                        }
                    }
                });
            }

        });
    });
<?php $this->Html->scriptEnd(); ?>