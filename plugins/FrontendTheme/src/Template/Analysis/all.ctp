<div class="popover_content" style="display: none">
    <ul class="list-unstyled pr-15 pl-15 pt-10 mb-15">
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-edit"><i class="fa fa-edit"></i> <?= __('Edit'); ?></a></li>
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-approve"><i class="fa fa-check"></i> <?= __('Approve'); ?></a></li>
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-make-copy"><i class="fa fa-copy"></i> <?= __('Make copy'); ?></a></li>
        <?php if (in_array($accountType, ['PROFESSIONAL', 'EXPERT'])): ?>
            <li class="popover-content-li"><a href="javascript:;" class="text-black popover-share-with-client"><i class="fa fa-share"></i> <?= __('Share with Client'); ?></a></li>
            <li class="popover-content-li"><a href="javascript:;" class="text-black popover-share-with-team"><i class="fa fa-share-alt"></i> <?= __('Share with Team'); ?></a></li>
            <li class="popover-content-li"><a href="javascript:;" class="text-black popover-microsoft-word"><i class="fa fa-file-word-o"></i> <?= __('Microsoft Word'); ?></a></li>
        <?php endif; ?> 
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-print"><i class="fa fa-print"></i> <?= __('Print'); ?></a></li>
    </ul>
</div>
<div class="container">
    <!--    <div class="heading-title heading-line-single text-center mt-30">
            <h3><?= __('Saved Analysis'); ?></h3>
        </div>-->
    <div class="row mt-20">
        <div class="col-lg-4 col-md-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <h3><?= __('Saved Analysis'); ?></h3>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
            <div class="mt-p-2 float-right">
                <?= $this->element('Links/quick_links'); ?>
            </div>
        </div>
    </div>
    <!-- ALERT -->
    <?= $this->Flash->render() ?>
    <!-- /ALERT -->
    <div class="row mb-10">
        <div class="col-lg-6 col-md-6 col-sm-12">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="float-right">
                <a href="#" class="btn btn-danger btn-sm delete-analysis-button disabled" data-toggle = "modal" data-target = "#deleteModal"><i class="fa fa-trash white"></i><?= __('Delete'); ?>&nbsp;<span class="checked_count"></span></a>
                <a href="#" class="btn btn-primary btn-sm" data-toggle = "modal" data-target = "#addModal"><i class="fa fa-plus white"></i><?= __('New'); ?></a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-vertical-middle analysis-table"
            data-delete-url = "<?= $this->Url->build(['_name' => 'delete_analysi']); ?>"
            data-print-url = "<?= $this->Url->build(['_name' => 'print_analysi']); ?>"
            data-doc-print-url = "<?= $this->Url->build(['_name' => 'print_analysis-doc']); ?>"
            data-edit-partial-url = "<?= $this->Url->build(['_name' => 'analysis_partial']); ?>"
            data-approve-url = "<?= $this->Url->build(['_name' => 'analysis_approve']); ?>"
            data-make-copy-url = "<?= $this->Url->build(['_name' => 'analysis_make_copy']); ?>"
            data-share-team-url = "<?= $this->Url->build(['_name' => 'analysis_share_with_team']); ?>">
            <thead>
                <tr>
                    <th class="text-center">
                        <label class="checkbox">
                            <input type="checkbox" class="checkAll" name="checkAll" value="1">
                            <i></i> &nbsp;
                        </label>
                    </th>
                    <th class="text-center"><?= __('Name'); ?></th>
                    <th class="text-center"><?= __('Type'); ?></th>
                    <th class="text-center"><?= __('Date Created'); ?></th>
                    <th class="text-center"><?= __('Last Modified'); ?></th>
                    <th class="text-center"><?= __('Approval Status'); ?></th>
                    <th class="text-center"><?= __('Watch List'); ?></th>
                    <th class="text-center"><?= __('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allAnalysis as $analysi): ?>
                    <tr class="analysis analysis-<?= $analysi->id; ?>">
                        <td class="text-center">
                            <label class="checkbox">
                                <input type="checkbox" class="checkbox_analysis" value="<?= $analysi->id; ?>">
                                <i></i> &nbsp;
                            </label>
                        </td>
                        <td class="text-center">
                            <?= $this->Html->link($analysi['name'], [
                                'lang' => $analysi['company']['exchange']['country']['market'],
                                '_name' => 'analysis_detail',
                                'key' => $analysi['id'] . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5)
                            ], [
                                'escape' => false
                            ]);?>        
                        </td>
                        <td class="text-center"><?= $analysi->analysis_type->name; ?></td>
                        <td class="text-center"><?= $analysi->created_at->nice(); ?></td>
                        <td class="text-center"><?= $analysi->created_at->nice(); ?></td>
                        <?php if ($analysi->approve): ?>
                            <td class="text-center"><span class="badge badge-success" id='<?= 'approval' . $analysi->id ?>'><?= __('Approved'); ?></span></td>
                        <?php else: ?>
                            <td class="text-center"><span class="badge badge-danger" id='<?= 'approval' . $analysi->id ?>'><?= __('No Approval'); ?></span></td>
                        <?php endif; ?>
                        <td>
                            <?= $this->Html->link('<i class="fa fa-line-chart white"></i>' . __('View'), [
                                '_name' => 'watchlist_all'
                            ], [
                                'tabindex' => '-1',
                                'class' => 'btn btn-linke',
                                'escape' => false
                            ]);?>
                        </td>
                        <td>
                            <a href="#" data-url="<?= $this->Url->build(['lang' => $analysi['company']['exchange']['country']['market'], '_name' => 'analysis_detail',
                                'key' => $analysi['id'] . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5)]);?>" class="btn btn-default my-popover-action" data-toggle="popover" data-id="<?= $analysi->id; ?>"  data-container="body">
                                <i class="fa fa-cog white"></i><?= __('Action'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <ul class="pagination justify-content-center">
            <?= $this->Paginator->prev('« ' . __('Previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Next') . ' »') ?>
        </ul>
    </div>
</div>

<!--modal delete -->
<div class="modal fade bs-example-modal-full" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= __('Are you sure you want to delete ?'); ?>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>

                    <?= $this->Form->button(__('<i class="fa fa-check"></i> Delete'), [
                        'type' => 'button',
                        'escape' => false,
                        'class' => 'btn btn-danger modal_delete_button'
                    ]);?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end modal delete-->

<!--modal add-->
<div class="modal fade bs-example-modal-full" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Add New Analysis</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create('Analysis', [
                    'class' => 'analysi_form_modal',
                    'id' => 'analysi_form_modal',
                    'url' => ['_name' => 'add_modal_analysi']
                ]); ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-10">
                        <?= $this->Form->textarea('text', [
                            'type' => 'text',
                            'data-height' => '400',
                            'data-lang' => 'en-US',
                            'class' => 'summernote form-control text_analysis',
                            'placeholder' => 'My notes/Analysis'
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="select">
                                    <?= __('Name'); ?>
                                    <?= $this->Form->control('name', [
                                        'required' => true,
                                        'label' => false,
                                        'placeholder' => 'Name',
                                        'class' => 'form-control'
                                    ]); ?>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="select">
                                    <?= __('Type'); ?>
                                    <?= $this->Form->select('type', \App\Model\Service\Core::$investmentPreferences, [
                                        'class' => 'form-control select2',
                                        'required' => true
                                    ]); ?>
                                    <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="select">
                                    <?= __('Symbol'); ?>
                                    <?= $this->Form->control('symbol', [
                                        'required' => true,
                                        'label' => false,
                                        'class' => 'form-control',
                                        'placeholder' => 'Symbol'
                                    ]); ?>
                                    <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="select">
                                    <?= __('Watch List'); ?>
                                    <?= $this->Form->select('watch_list', $watchlistStock, [
                                        'multiple' => true,
                                        'class' => 'form-control select2 company'
                                    ]); ?>
                                    <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="select">
                                    <?= __('News'); ?>
                                    <?= $this->Form->select('news', $newsList, [
                                        'multiple' => true,
                                        'class' => 'form-control select2 company'
                                    ]); ?>
                                    <b class="tooltip tooltip-bottom-right"><?= __('Currency Pair') ?></b>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="input">
                                    <?= __('Tags'); ?>
                                    <?= $this->Form->control('tags', [
                                        'label' => false,
                                        'class' => 'form-control',
                                        'placeholder' => 'Tags'
                                    ]); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>

                        <?= $this->Form->button(__('<i class="fa fa-check"></i> SAVE'), [
                            'type' => 'submit',
                            'escape' => false,
                            'class' => 'btn btn-primary'
                        ]); ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<!--end add moda-->

<div class="modal fade bs-example-modal-full" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
    <div class="modal-dialog modal-full editModalBody">
    </div>
</div>

<!--alert-->
<div data-notify="container" id="alert-success-copy" class="col-sm-2 col-lg-2 col-md-2 alert btn-primary alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>
<div data-notify="container" id="alert-danger-copy" class="col-sm-2 col-lg-2 col-md-2 alert btn-danger alert-box" role="alert" data-notify-position="top-right">
    <p class="alert-msg"></p>
</div>
<!--end alert-->

<!--share team modal-->

<div class="modal fade share-team-modal-lg" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- header modal -->
            <div class="modal-header">
                <h4 class="text-center">Share with Team</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <!-- body modal -->
            <div class="modal-body">
                <?= $this->Form->create(null, ['url' => ['action' => 'shareTeam'], 'type' => 'post', 'class' => 'm-0 sky-form ']); ?>
                <div class="box-light">
                    <div class="box-inner">
                        <header>
                        </header>
                        <fieldset class="m-0" style="padding: 10px">
                            <?= $this->Form->hidden('analysis_url', ['class' => 'form-control analysis_url']); ?>
                            <div class="col-lg-6 col-md-6 col-sm-6 pull-right">
                                <div class="form-group">
                                    <label class="select"><?= __('Users'); ?></label>
                                    <?= $this->Form->select('private_user', $allUsers, ['required' => true, 'multiple' => 'multiple', 'class' => 'form-control select2 users', 'error' => true]); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="select"><?= __('Private Room Name'); ?></label>
                                    <?= $this->Form->select('private_name', $private, ['required' => true, 'class' => 'form-control', 'error' => true]); ?>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?= __('Message'); ?></label>
                                    <?= $this->Form->textarea('comment', ['class' => 'form-control', 'rows' => '5']); ?>
                                </div>
                            </div>
                            <?= $this->Form->button('Add', ['type' => 'submit', 'class' => 'btn btn-success mt-20 mr-10 float-right send-team']); ?>
                        </fieldset>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>

<!--end share team modal-->

<?php
$this->Html->script(
        [
    'analysis/all_analysis.js'
        ], ['block' => 'script']
);
?>
