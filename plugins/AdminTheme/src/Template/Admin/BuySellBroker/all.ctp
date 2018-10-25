<section id="middle">
    <header id="page-header">
        <h1><?= __('All Buy Sell Broker'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Buy Sell Broker'), '#'); ?></li>
            <li class="active"><?= __('All Buy Sell Broker'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('All Buy Sell Broker'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><?= $this->Html->link(__('Add Buy Sell Broker'), ['_name' => 'add_buy_sell_broker'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?></li>
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
                <div class="table-responsive12 table-responsive">
                    <table id="stock-grid"  class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= __('Company Name'); ?></th>
                                <?php foreach ($all_brokers as $broker): ?>
                                    <th><?= $broker['first_name']; ?></th>
                                <?php endforeach; ?>
                                <th><?= __('Date'); ?></th>
                                <th><?= __('Edit'); ?></th>
                                <th><?= __('Delete'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($brokers_details as $val): ?>
                                <tr>
                                    <td><?= $val['symbol']; ?></td>
                                    <?php foreach ($all_brokers as $broker): ?>
                                        <td>
                                            <?php
                                            echo \App\Model\Table\BuySellBrokerTable::getBuySellBroker($val['company_id'], $broker['id']);
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td nowrap><?= $val['created_at']->nice(); ?></td>
                                    <td>
                                        <?php $edit = $this->Url->build(['_name' => 'edit_buy_sell_broker', 'id' => $val["id"]]); ?>
                                        <a href="<?= $edit; ?>" class="edit btn btn-3d btn-sm btn-reveal btn-success">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                            <span><?= __('Edit'); ?></span>
                                        </a>
                                    </td>
                                    <td>
                                        <?php $delete = $this->Url->build(['_name' => 'delete_buy_sell_broker', 'id' => $val["id"]]); ?>
                                        <button class="btn btn-danger btn-3d btn-sm"
                                                data-toggle="modal" data-target="#confirm-delete" 
                                                data-name="" 
                                                data-url="<?= $delete; ?>" 
                                                type="submit"><?= __('Delete'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
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

<!--<section id="middle">
    <header id="page-header">
        <h1><?= __('All Buy Sell Broker'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Buy Sell Broker'), '#'); ?></li>
            <li class="active"><?= __('All Buy Sell Broker'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('All Buy Sell Broker'); ?></strong>  panel title 
                </span>

                 right options 
                <ul class="options pull-right list-inline">
                    <li><?= $this->Html->link(__('Add Buy Sell Broker'), ['_name' => 'add_buy_sell_broker'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?></li>
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                 /right options 

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="padding-20">
                         ALERT 
<?= $this->Flash->render() ?>
                         /ALERT 
                    </div>
                </div>
                <div class="table-responsive12 table-responsive">
                    <table id="stock-grid"  class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= __('ID'); ?></th>
                                <th><?= __('Company Name'); ?></th>
                                <th><?= __('Broker Name'); ?></th>
                                <th><?= __('Status'); ?></th>
                                <th><?= __('Edit'); ?></th>
                                <th><?= __('Delete'); ?></th>
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
                var dataTable = $('#stock-grid').DataTable({
                    "columns": [{
                            "orderable": true
                        },{
                            "orderable": true
                        },{
                            "orderable": true
                        },{
                            "orderable": true
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
                        url: '<?= $this->Url->build(['_name' => 'data_table_brokers_buy_sell']); ?>',
                        type: "GET",

                        error: function () {
                            $(".stock-grid-error").html("");
                            $("#stock-grid").append('<tbody class="stock-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#stock-grid_processing").css("display", "none");
                        }
                    }
                });
            }

        });
    });-->
<script>
    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('url'));
        $(this).find('.modal-body').text($(e.relatedTarget).data('name'));
    });
</script>