<div class="col-lg-3 col-md-3 col-sm-4">

    <div class="thumbnail text-center">
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
