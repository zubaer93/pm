<?php
$message_parent = \App\Model\Table\MessagesTable::getParentData($parent_id);
if (!is_null($message_parent)):
    ?>
    <div class="container">
        <div class="ml-90">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12 mt-5">
                    <div class="message-header">
                        <div class="thumbnail float-left avatar mr-20">
                            <?=
                            $this->Html->link($this->Html->image($message_parent->app_user->avatarPath, [
                                        'width' => '60',
                                        'height' => '60',
                                        'class' => 'user_avatar'
                                    ]), ['_name' => 'user_name',
                                'username' => $message_parent->app_user->username
                                    ], ['escape' => false]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 ml-5m">
                    <div class="row">
                        <span class=""><?= (isset($message_parent->created) ? $message_parent->created->nice() : ''); ?></span>

                        <span class="mr-5 ml-10"> <?= __('Rate:') ?></span>
                        <div class="stars mb-10m" data-id="<?= $message_parent->id; ?>">
                            <form action="">
                                <?php
                                for ($i = 5; $i >= 1; $i--):
                                    $rand = mt_rand();
                                    ?>
                                    <?php if (isset($message_parent->ratings[0]->grade) && App\Model\Table\RatingsTable::getAverageRanking($message_parent->id) == $i): ?>
                                        <input class="star star-<?= $i ?>" <?= (!empty($authUser) && $authUser['id'] == $message_parent->user_id) ? 'disabled' : '' ?> checked id="star-<?= $i; ?><?= $rand; ?>" type="radio" value="<?= $i; ?>" name="star"/>
                                    <?php else: ?>
                                        <input class="star star-<?= $i ?>" <?= (!empty($authUser) && $authUser['id'] == $message_parent->user_id) ? 'disabled' : '' ?> id="star-<?= $i; ?><?= $rand; ?>" type="radio" value="<?= $i; ?>" name="star"/>
                                    <?php endif; ?>
                                    <label class="star star-<?= $i ?>" attr_value ="<?= $i; ?>" for="star-<?= $i; ?><?= $rand; ?>"></label>
                                <?php endfor; ?>
                            </form>
                        </div>
                        <span class="ml-5 mr-10">
                            <?= App\Model\Table\RatingsTable::getAverageRanking($message_parent->id) . '.0'; ?>
                        </span>
                    </div>
                    <div class="row">
                        <a href="
                        <?=
                        $this->Url->build([
                            '_name' => 'user_name',
                            'username' => $message_parent->app_user->username]);
                        ?>"
                           class="message-username"><b> 
                                <?= $message_parent->app_user->username . ' - ' ?></b>
                            <cite style="color: black"  class="fs-12"> <?=
                                $message_parent->app_user->experience_by_id . ' '
                                . '' . $message_parent->app_user->investment_style_by_id
                                ?></cite>
                        </a>
                    </div>
                    <div class="row">
                        <div class="message-content">
                            <div class="message-body">
                                <?php
                                $url = $this->Url->build([
                                    'plugin' => false,
                                    'controller' => 'Companies',
                                    'action' => 'symbol']);
                                ?>
                                <?= preg_replace("/!(\w+)/", "<a href=\"$url/\\1\">!\\1</a>", $message_parent->message); ?>
                            </div>
                            <div class="message-tools"></div>

                        </div>
                    </div>
                </div>
                <?php if (isset($message_parent->screenshot_message[0]['data'])): ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
                        <div class="mydiv_iframe" >
                            <img src="data:image/jpeg;base64,<?= $message_parent->screenshot_message[0]['data']; ?>" height=\"200\" />
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>