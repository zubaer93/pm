<section id="middle">
    <header id="page-header">
        <h1><?= __('All Pages'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Pages'), '#'); ?></li>
            <li class="active"><?= __('All Pages'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('All Pages'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><?= $this->Html->link(__('Add Page'), ['_name' => 'add_page'], ['class' => 'btn btn-3d btn-xs btn-green opt']); ?></li>
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                <!-- /right options -->

            </div>

            <div class="panel-body table-responsive">
                <div class="row">
                    <div class="padding-20">
                        <!-- ALERT -->
                        <?= $this->Flash->render() ?>
                        <!-- /ALERT -->
                    </div>
                </div>
                <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                    <thead>
                        <tr>
                            <th><?= __('ID'); ?></th>
                            <th><?= __('Name'); ?></th>
                            <th><?= __('Position'); ?></th>
                            <th><?= __('Created At'); ?></th>
                            <th><?= __('Enable'); ?></th>
                            <th><?= __('Edit'); ?></th>
                            <th><?= __('Delete'); ?></th>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td>
                                    <?= $page['id']; ?>
                                </td>
                                <td>
                                    <?= $page['name']; ?>
                                </td>
                                <td>
                                    <?= $page['position']; ?>
                                </td>

                                <td>
                                    <?= $page['created_at']; ?>
                                </td>
                                <td>
                                    <label class="switch switch-info btn-sm">
                                        <input class="disable"
                                               onchange='window.location.assign(
                                                               "<?=
                                               $this->Url->build([
                                                   "_name" => ($page['enable']) ? "enable_page" : "disable_page",
                                                   "id" => $page['id']
                                               ]);
                                               ?>")'
                                               type="checkbox" <?= (!$page['enable']) ? 'checked' : '' ?> />
                                        <span class="switch-label" data-on="YES" data-off="NO"></span>
                                    </label>
                                </td>
                                <td>
                                    <?=
                                    $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-pencil'))
                                            . $this->Html->tag('span', __('Edit')), [
                                        '_name' => 'edit_page',
                                        'id' => $page['id']
                                            ], [
                                        'escape' => false,
                                        'class' => 'edit btn btn-3d btn-sm btn-reveal btn-success'
                                            ]
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?=
                                    $this->Form->button(__('Delete'), ['class' => 'btn btn-danger btn-3d btn-sm',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#confirm-delete',
                                        'data-name' => $page['name'],
                                        'data-url' => 'delete/' . $page['id']
                                    ])
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

    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('url'));
        $(this).find('.modal-body').text($(e.relatedTarget).data('name'));
    });
</script>