<div class="tab-pane active" id="followers">
    <?php foreach ($followers as $follower): ?>
        <div class="row tab-post"><!-- post -->
            <div class="col-lg-3 col-md-12 col-sm-12 mt-5">
                <div class="message-header">
                    <div class="thumbnail float-left avatar mr-20">
                        <?= $this->Html->link($this->Html->image($follower->follower->avatarPath, ['width' => '60', 'height' => '60', 'class' => 'user_avatar']), [
                                '_name' => 'user_name',
                                'username' => $follower->follower->username
                            ], [
                                'escape' => false
                            ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 ">
                <div class="row">
                    <span class="date"><?= $follower->created_at->nice() ?></span> &nbsp;
                    <?= $this->element('Users/rating', ['rating' => \App\Model\Service\Core::getUserRating($follower->follower->id)]); ?>
                </div>
                <div class="row">
                    <a href="<?= $this->Url->build(['_name' => 'user_name', 'username' => $follower->follower->username]); ?>"
                       class="message-username">
                           <?php if (!is_null($authUser) && $authUser['id'] != $follower->follower->id): ?>
                            <b> 
                                <?= $follower->follower->username . ' - ' ?>
                            </b>
                        <?php else: ?>
                            <b> 
                                <?= $follower->follower->username . ' - ' ?>
                            </b>
                        <?php endif; ?>
                        <cite style="color: black"  class="fs-12">
                            <span class="experience"> <?= $follower->follower->experience_by_id ?>
                            </span>
                            <span class="investment">
                                <?= $follower->follower->investment_style_by_id
                                ?></span>
                        </cite>
                    </a>
                </div>
            </div>
        </div><!-- /post -->
    <?php endforeach; ?>
</div>
<div class="tab-pane" id="following">
    <?php foreach ($following as $follower): ?>
        <div class="row tab-post"><!-- post -->
            <div class="col-lg-3 col-md-12 col-sm-12 mt-5">
                <div class="message-header">
                    <div class="thumbnail float-left avatar mr-20">
                        <?php if (file_exists(WWW_ROOT . $follower->following->avatarPath)): ?>
                            <?=
                            $this->Html->link($this->Html->image($follower->following->avatarPath, [
                                        'width' => '60',
                                        'height' => '60',
                                        'class' => 'user_avatar'
                                    ]), ['_name' => 'user_name',
                                'username' => $follower->following->username
                                    ], ['escape' => false]);
                            ?>
                        <?php else: ?>
                            <?=
                            $this->Html->link($this->Html->image($follower->following->avatar, [
                                        'width' => '60',
                                        'height' => '60',
                                        'class' => 'user_avatar'
                                    ]), ['_name' => 'user_name',
                                'username' => $follower->following->username
                                    ], ['escape' => false]);
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 ">
                <div class="row">
                    <span class="date"><?= $follower->created_at->nice() ?></span> &nbsp;
                    <?= $this->element('Users/rating', ['rating' => \App\Model\Service\Core::getUserRating($follower->following->id)]); ?>

                </div>
                <div class="row">
                    <a href="
                    <?=
                    $this->Url->build([
                        '_name' => 'user_name',
                        'username' => $follower->following->username]);
                    ?>"
                       class="message-username">
                           <?php if (!is_null($authUser) && $authUser['id'] != $follower->following->id): ?>
                            <b> 
                                <?= $follower->following->username . ' - ' ?>
                            </b>
                        <?php else: ?>
                            <b> 
                                <?= $follower->following->username . ' - ' ?>
                            </b>
                        <?php endif; ?>
                        <cite style="color: black"  class="fs-12">
                            <span class="experience"> <?= $follower->following->experience_by_id ?>
                            </span>
                            <span class="investment">
                                <?= $follower->following->investment_style_by_id
                                ?></span>
                        </cite>
                    </a>
                </div>
            </div>
        </div><!-- /post -->
    <?php endforeach; ?>
</div>
