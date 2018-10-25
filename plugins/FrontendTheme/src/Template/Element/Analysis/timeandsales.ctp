<div class="table-responsive12 table-responsive" style="min-height: 200px; max-height: 200px;">
    <table id="time-and-sales-grid"  class="table table-striped">
        <thead>
            <tr>
                <th class=""><?= __('Price') ?> </th>
                <th class=""></th>
                <th class=""><?= __('Quantity') ?></th>
                <th class=""><?= __('Tot.Volume') ?></th>
                <th class=""><?= __('Time') ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php if(isset($partialJS)): ?>
<?= $this->element('FinancialStatement/dataTable', ['symbol' => $companyInfo['symbol'], 'partialJS' => true]); ?>
<?php endif; ?>
                               