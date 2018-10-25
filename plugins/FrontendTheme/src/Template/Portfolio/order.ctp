<?= $this->Html->css('FrontendTheme.../css/layout-datatables'); ?>

<div class="container mt-20">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <?= $this->Flash->render(); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-0">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6">
        <h2 class="text-center mt-15">
            <?= __('All Orders'); ?>
        </h2>
    </div>
    <!--    <div class="col-lg-2 col-md-2 col-sm-6"></div>-->
    <div class="col-lg-2 col-md-2 col-sm-6">
        <div class="mt-p-3 mt-20 float-right">
            <?= $this->element('Links/quick_links'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-1 col-md-1 col-sm-6"></div>
    <div class="col-md-10 col-lg-10 col-sm-12 mb-35" id="middle">
        <div id="content" class="padding-20">
            <div id="panel-1" class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="table-responsive12 table-responsive">
                        <table id="order-grid"  class="table table-striped">
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
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-6"></div>
</div>
<?php $this->Html->scriptStart(['block' => true]); ?>
loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

if (jQuery().dataTable) {
var dataTable = $('#order-grid').DataTable({
"columns": [
{
"orderable": true
}, {
"orderable": true
}, {
"orderable": true
}, {
"orderable": true
},{
"orderable": true
},{
"orderable": true
},{
"orderable": true
}, {
"orderable": true
}, {
"orderable": true
},
{
"orderable": true
}, {
"orderable": true
},{
"orderable": true
},{
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
url: '<?= $this->Url->build(['_name' => 'data_table_order_search']); ?>',
type: "get",

error: function () {
$(".order-grid-error").html("");
$("#order-grid").append('<tbody class="order-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
$("#order-grid_processing").css("display", "none");
}
}
});
}

});
});

<?php $this->Html->scriptEnd(); ?>
<script>

</script>
