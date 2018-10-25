<?php if (isset($_SESSION['Flash']['no_information'][0]['message'])): ?>
    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?= $_SESSION['Flash']['no_information'][0]['message']; ?>
                    <?php unset($_SESSION['Flash']['no_information']); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onclick="$(this).parents('.modal').hide();" data-dismiss="modal"><?= __('Cancel'); ?></button>
                    <?=
                    $this->Html->link(
                            $this->Html->tag('i', '', ['class' => 'fa fa-gears']) . __('SETTINGS'), [
                        '_name' => 'settings'
                            ], [
                        'class' => 'btn btn-primary',
                        'tabindex' => '-1',
                        'escape' => false
                            ]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>