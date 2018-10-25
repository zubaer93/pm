<section id="middle">
    <!-- page title -->
    <header id="page-header">
        <h1><?= __('Posts'); ?></h1>
        <ol class="breadcrumb">
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('Posts List'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><?= $this->Html->link(__('Add Post'), ['_name' => 'add_post'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?></li>
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                <!-- /right options -->

            </div>

            <!-- panel content -->
            <div class="panel-body">
                <div class="row">
                    <div class="padding-20">
                        <!-- ALERT -->
                        <?= $this->Flash->render() ?>
                        <!-- /ALERT -->
                    </div>
                </div>
                <div class="table-responsive12 table-responsive">
                    <table id="post-grid"  class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><?= __('ID'); ?></th>
                                <th class="width-30 text-center"><?= __('Img'); ?></th>
                                <th class = "text-center"><?= __('Username'); ?></th>
                                <th class = "text-center"><?= __('Company'); ?></th>
                                <th class = "text-center"><?= __('Message'); ?></th>
                                <th class = "text-center"><?= __('Market'); ?></th>
                                <th class = "text-center"><?= __('Action'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /panel content -->

            <!-- panel footer -->
            <div class="panel-footer">

                <!-- pre code -->
                <div class="text-left">

                </div>

                <!-- /pre code -->

            </div>
            <!-- /panel footer -->
        </div>
    </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Are you sure you want to delete?'); ?>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?= __('Delete'); ?></a>
            </div>
        </div>
    </div>
</div>
<script>
    loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
        loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

            if (jQuery().dataTable) {
                var dataTable = $('#post-grid').DataTable({
                    "columns": [{
                            "orderable": true
                        }, {
                            "orderable": false
                        }, {
                            "orderable": true
                        }, {
                            "orderable": false
                        }, {
                            "orderable": true
                        }, {
                            "orderable": true
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
                        url: '<?= $this->Url->build(['_name' => 'data_table_post']); ?>',
                        type: "post",

                        error: function () {
                            $(".post-grid-error").html("");
                            $("#post-grid").append('<tbody class="post-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#post-grid_processing").css("display", "none");
                        }
                    }
                });
            }

        });
    });
    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('url'));
        $(this).find('.modal-body').text($(e.relatedTarget).data('title'));
    });
</script>