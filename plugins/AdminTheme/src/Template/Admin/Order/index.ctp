<section id="middle">
    <header id="page-header">
        <h1><?= __('All Orders'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Order'), '#'); ?></li>
            <li class="active"><?= __('All Orders'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('All Orders'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                <!-- /right options -->

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="padding-20">
                        <!-- ALERT -->
                        <?= $this->Flash->render() ?>
                        <!-- /ALERT -->
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="company-grid"  class="table table-bordered table-striped nomargin">
                        <thead>
                        <tr>
                            <th><?= __('Name'); ?></th>
                            <th><?= __('Symbol'); ?></th>
                            <th><?= __('Compony Price'); ?></th>
                            <th><?= __('User Name'); ?></th>
                            <th><?= __('Broker'); ?></th>
                            <th><?= __('Order Type'); ?></th>
                            <th><?= __('Fees'); ?></th>
                            <th><?= __('Quantity'); ?></th>
                            <th><?= __('Market'); ?></th>
                            <th><?= __('Action'); ?></th>
                            <th><?= __('Total'); ?></th>
                            <th><?= __('Created At'); ?></th>
                            <th><?= __('Status'); ?></th>
                            <th><?= __('Approve'); ?></th>
                            <th><?= __('Reject'); ?></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('<p class="modal-header-name"></p> '); ?>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('No'); ?></button>
                <a class="btn btn-danger btn-ok"><?= __('Yes'); ?></a>
            </div>
        </div>
    </div>
</div>

<script>
    loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
        loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

            if (jQuery().dataTable) {
                var dataTable = $('#company-grid').DataTable({
                    "columns": [{
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": true
                    }, {
                        "orderable": false
                    }, {
                        "orderable": true
                    }, {
                        "orderable": false
                    }, {
                        "orderable": false
                    }, {
                        "orderable": false
                    }],
                    "lengthMenu": [
                        [10, 20, 50, 100, 250],
                        [10, 20, 50, 100, 250] // change per page values here
                    ],
                    // set the initial value
                    "pageLength": 20,
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
                        url: '<?= $this->Url->build(['_name' => 'data_table_order']); ?>',
                        type: "get",

                        error: function () {
                            $(".company-grid-error").html("");
                            $("#company-grid").append('<tbody class="company-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#company-grid_processing").css("display", "none");
                        }
                    }
                });
            }

        });
    });
    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('url'));
        $(this).find('.modal-header-name').text($(e.relatedTarget).data('name'));
    });
</script>