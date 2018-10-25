<div class="tab-pane active" id="calls">
<div class="table-scrollable table-responsive">
    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="datatable_sample">
        <thead>
            <tr>
                <th class="text-align-center"><?= __('Price') ?> </th>
                <th class="text-align-center"><?= __('Bid') ?></th>
                <th class="text-align-center">
                    <?= __('Ask') ?>
                </th>
                <th class="text-align-center" data-toggle="tooltip" title="<?= __('Volume'); ?>">
                    <?= __('Vol.') ?>
                </th>
                <th class="text-align-center"><?= __('Open Int') ?></th>
                <th class="text-align-center" ><?= __('Strike') ?></th>
                <th class="text-align-center" data-toggle="tooltip" title="<?= __('Expiration'); ?>"><?= __('Exp.'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($options['calls'])): ?>
                <?php foreach ($options['calls'] as $key => $val): ?>
                    <?php if ($key > 1): ?>
                        <tr class="trader">
                            <td class="text-align-right">
                                <?= $val['lastPrice']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['bid']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['ask']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['volume']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['openInterest']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['strike']; ?>
                            </td>
                            <td class="text-align-right " nowrap>
                                <?= date('Y-m-d',$val['expiration']); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
<div class="tab-pane" id="puts">
<div class="table-scrollable table-responsive">
    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="datatable_sample_puts">
        <thead>
            <tr>
                <th class="text-align-center"><?= __('Price') ?> </th>
                <th class="text-align-center"><?= __('Bid') ?></th>
                <th class="text-align-center">
                    <?= __('Ask') ?>
                </th>
                <th class="text-align-center" data-toggle="tooltip" title="<?= __('Volume'); ?>">
                    <?= __('Vol.') ?>
                </th>
                <th class="text-align-center"><?= __('Open Int') ?></th>
                <th class="text-align-center" ><?= __('Strike') ?></th>
                <th class="text-align-center" data-toggle="tooltip" title="<?= __('Expiration'); ?>"><?= __('Exp.'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($options['puts'])): ?>
                <?php foreach ($options['puts'] as $key => $val): ?>
                    <?php if ($key > 1): ?>
                        <tr class="trader">
                            <td class="text-align-right">
                                <?= $val['lastPrice']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['bid']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['ask']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['volume']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['openInterest']; ?>
                            </td>
                            <td class="text-align-right">
                                <?= $val['strike']; ?>
                            </td>
                            <td class="text-align-right" nowrap>
                                <?= date('Y-m-d',$val['expiration']); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
<?php $this->Html->scriptStart(['block' => true]); ?>
    loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function () {
    loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function () {

if (jQuery().dataTable) {

        var table = jQuery('#datatable_sample');
        
        table.dataTable({
                "lengthChange": true,
                "columns": [{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
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
                "columnDefs": [{// set default column settings
                'orderable': true,
                'targets': [0]
                }, {
                "searchable": true,
                "targets": [0]
                }],
                "order": [
                [1, "DESC"]
                ] // set first column as a default sort by asc
        });
}
if (jQuery().dataTable) {

        var table = jQuery('#datatable_sample_puts');
        
        table.dataTable({
                "lengthChange": true,
                "columns": [{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
                    "orderable": true
                },{
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
                "columnDefs": [{// set default column settings
                'orderable': true,
                'targets': [0]
                }, {
                "searchable": true,
                "targets": [0]
                }],
                "order": [
                [1, "DESC"]
                ] // set first column as a default sort by asc
        });
}

});
});
<?php $this->Html->scriptEnd(); ?>
