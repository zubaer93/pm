<div class="tab-pane fade" id="avatar">
    <?= $this->Form->create($user, ['url' => ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'editAvatar', $user->id], 'class' => 'clearfix', 'type' => 'file']); ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="thumbnail">
                    <?php if(file_exists(WWW_ROOT.$user->avatarPath)): ?>
                        <?= $this->Html->image(
                            $user->avatarPath,
                            ['width' => '460', 'height' => '460', 'class' => 'img-fluid']
                        ); ?>
                    <?php else: ?>
                        <?= $this->Html->image(
                            $user->avatar,
                            ['width' => '460', 'height' => '460', 'class' => 'img-fluid']
                        ); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-9 col-sm-8">
                <div class="sky-form m-0">
                    <label class="label"><?= __('Select File') ?></label>
                    <label for="file" class="input input-file">
                        <div class="button">
                            <?= $this->Form->hidden('id', ['label' => false, 'id' => 'id', 'name' => 'id', 'value' => $user->id]) ?>
                            <?= $this->Form->file('avatar', ['label' => false, 'id' => 'file', 'onchange' => 'this.parentNode.nextSibling.nextSibling.value = this.files[0].name']); ?>
                            <?= __('Browse'); ?>
                        </div>
                        <input type="text" readonly>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="margiv-top10">
        <?= $this->Form->button('Save Changes', ['class' => 'btn btn-primary']); ?>
        <a href="#" class="btn btn-default"> <?= __('Cancel') ?> </a>
    </div>
    <?= $this->Form->end(); ?>
</div>