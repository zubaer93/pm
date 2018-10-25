<section id="middle">
    <header id="page-header">
        <h1><?= __('Sector performances'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Sector performances'), '#'); ?></li>
            <li class="active"><?= __('Sector performances'); ?></li>
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
                    <strong><?= __("Sector performances List"); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li>
                        <?= $this->Html->link(__('Add Sector performance'), ['_name' => 'add_sector_performance'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?> 
                    </li>
                </ul>
                <!-- /right options -->

            </div>
            <div class="panel-body table-responsive">
                <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                    <thead>
                        <tr>
                            <th><?= __('ID'); ?></th>
                            <th><?= __('Sector'); ?></th>
                            <th><?= __('Percent'); ?></th>
                            <th><?= __('Edit'); ?></th>
                            <th><?= __('Delete'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sectorPerformances as $val): ?>
                            <tr>
                                <td>
                                    <?= $val['id']; ?>
                                </td>
                                <td>
                                    <?= $val['name']; ?>
                                </td>
                                <td>
                                    <?= $val['percent']; ?>
                                </td>
                                <td>
                                    <?=
                                    $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-pencil'))
                                            . $this->Html->tag('span', __('Edit')), [
                                        '_name' => 'edit_sector_performance',
                                        'id' => $val['id']
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
                                        '_name' => 'delete_sector_performance',
                                        'id' => $val['id']
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