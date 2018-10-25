<section id="middle">
    <header id="page-header">
        <h1><?= __('All Research Companies'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Research Market'), '#'); ?></li>
            <li class="active"><?= __('All Research Companies'); ?></li>
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
                    <strong><?= __("RESEARCH MARKET LIST"); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li>
                        <?= $this->Html->link(__('Add RESEARCH Company'), ['_name' => 'add_research_company'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?> 
                    </li>
                </ul>
                <!-- /right options -->

            </div>
            <div class="panel-body">
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
                    <?php foreach ($researchCompanies as $researchCompany): ?>
                        <tr>
                            <td>
                                <?= $researchCompany['id']; ?>
                            </td>
                            <td>
                                <?= $researchCompany['name']; ?>
                            </td>
                            <td>
                                <?= $researchCompany['order']; ?>
                            </td>
                            <td>
                                <?= $researchCompany['research_market']->name; ?>
                            </td>
                            <td>
                                <label class="switch switch-info btn-sm">
                                    <?php
                                        if ($researchCompany['status'] == 'enabled') {
                                            $checked = 'checked';
                                            $enable = $this->Url->build([
                                            "_name" => "disable_research_company",
                                            "id" => $researchCompany['id']
                                            ]);
                                        } else {
                                            $checked = '';
                                            $enable = $this->Url->build(['_name' => 'enable_research_company', 'id' => $researchCompany['id']]);
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
                                    '_name' => 'edit_research_company',
                                    'id' => $researchCompany['id']
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
                                    '_name' => 'delete_research_company',
                                    'id' => $researchCompany['id']
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
                    <?= $this->Paginator->prev('� '.__('Previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Next').' �') ?>
                </ul>
            </div>
        </div>
    </div>
</section>