<div class="col-lg-12 col-md-12 col-sm-23">
    <div class="thumbnail text-center">
        <?php if (file_exists(WWW_ROOT . $user->avatarPath)): ?>
            <?=
            $this->Html->image(
                    $user->avatarPath, ['width' => '460', 'height' => '460', 'class' => 'img-fluid']
            );
            ?>
        <?php else: ?>
            <?=
            $this->Html->image(
                    $user->avatar, ['width' => '460', 'height' => '460', 'class' => 'img-fluid']
            );
            ?>
        <?php endif; ?>
        <h3>
            <?=
            $this->Html->tag(
                    'span', $user->fullName, ['class' => 'full_name']
            )
            ?>
        </h3>
    </div>
</div>
