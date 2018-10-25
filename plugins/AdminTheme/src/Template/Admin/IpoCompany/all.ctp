<section id="middle">
    <header id="page-header">
        <h1><?= __('All Ipo Companies'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Ipo Market'), '#'); ?></li>
            <li class="active"><?= __('All Ipo Companies'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="row">
                <div class="padding-20">
                    <!-- ALERT -->
                    <?= $this->Flash->render() ?>
                    <!-- /ALERT -->
                </div>
            </div>
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __("IPO MARKET LIST"); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li>
                        <?= $this->Html->link(__('Add IPO Company'), ['_name' => 'add_ipo_company'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?> 
                    </li>
                </ul>
                <!-- /right options -->

            </div>
            <div class="panel-body table-responsive">
                <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                    <thead>
                        <tr>
                            <th><?= __('ID'); ?></th>
                            <th><?= __('Name'); ?></th>
                            <th><?= __('Order'); ?></th>
                            <th><?= __('Market'); ?></th>
                            <th><?= __('Enable'); ?></th>
                            <th><?= __('Edit'); ?></th>
                            <th><?= __('Delete'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ipoCompanies as $ipoCompany): ?>
                            <tr>
                                <td>
                                    <?= $ipoCompany['id']; ?>
                                </td>
                                <td>
                                    <?= $ipoCompany['name']; ?>
                                </td>
                                <td>
                                    <?= $ipoCompany['order']; ?>
                                </td>
                                <td>
                                    <?= $ipoCompany['ipo_market']->name; ?>
                                </td>
                                <td>
                                    <label class="switch switch-info btn-sm">
                                        <?php
                                        if ($ipoCompany['status'] == 'enabled') {
                                            $checked = 'checked';
                                            $enable = $this->Url->build(['_name' => 'disable_ipo_company', 'id' => $ipoCompany["id"]]);
                                        } else {
                                            $checked = '';
                                            $enable = $this->Url->build(['_name' => 'enable_ipo_company', 'id' => $ipoCompany["id"]]);
                                        }
                                        ?>
                                        <input class="disable"
                                               onchange='window.location.assign(
                                                               "<?= $enable ?>")'
                                               type="checkbox" <?= $checked; ?>>
                                        <span class="switch-label" data-on="YES" data-off="NO"></span>
                                    </label>
                                </td>
                                <td>
                                    <?=
                                    $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-pencil'))
                                            . $this->Html->tag('span', __('Edit')), [
                                        '_name' => 'edit_ipo_company',
                                        'id' => $ipoCompany['id']
                                            ], [
                                        'escape' => false,
                                        'class' => 'edit btn btn-3d btn-sm btn-reveal btn-success'
                                            ]
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?=
                                    $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-trash'))
                                            . $this->Html->tag('span', __('Delete')), [
                                        '_name' => 'delete_ipo_company',
                                        'id' => $ipoCompany['id']
                                            ], [
                                        'escape' => false,
                                        'class' => 'delete btn btn-3d btn-sm btn-reveal btn-danger',
                                        'onclick' => 'return confirm(\'Are you sure?\');'
                                            ]
                                    );
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <ul class="pagination justify-content-center">
                    <?= $this->Paginator->prev('« ' . __('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next') . ' »') ?>
                </ul>
            </div>
        </div>
    </div>
</section>