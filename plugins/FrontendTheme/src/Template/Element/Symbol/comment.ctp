<div class="toggle toggle-transparent toggle-noicon">
    <div class="toggle commnets-toggle">
        <label  class="text-right">
            <a href="javascript:;" class="btn btn-primary relative comments_post" data-message-id="<?= $message_id ?>">
                <span class="badge badge-dark badge-corner radius-3 comments_post_count" data-message-id="<?= $message_id ?>"><?= (int) $count ?></span>
                <i class="fa fa-comments fs-20 p-0"></i> <?= __(' Comment'); ?>
            </a>
        </label>
        <div class="toggle-content">
            <p>
            <div class="comments-list" id="message-id-<?= $message_id ?>"></div>
            <?php if ($this->request->session()->read('Auth.User')): ?>
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        <div class="thumbnail float-left avatar mr-20">
                            <?=
                            $this->Html->image(
                                    $avatarPath, ['width' => '30', 'height' => '30', 'class' => 'user_avatar']
                            );
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-11 col-md-11 col-sm-10">
                        <?php
                        $formOptions = [
                            'class' => 'formMessage',
                            'url' => ['controller' => 'Messages', 'action' => 'addComment']
                        ];
                        echo $this->Form->create('NewsComment', $formOptions);
                        ?>
                        <?php
                        $config = [
                            'class' => 'message-box form-control h-40',
                            'placeholder' => 'Write a comment...',
                            'data-maxlength' => '200',
                            'data-message-id' => $message_id,
                            'value' => '@' . $username . ' '
                        ];
                        echo $this->Form->textarea('message', $config);
                        ?>
                        <?= $this->Form->hidden('message_id', ['value' => $message_id]); ?>
                        <?php
                        $buttonOptions = [
                            'type' => 'submit',
                            'escape' => false,
                            'class' => 'btn btn-primary btn-send-message-comment btn-sm'
                        ];
                        echo $this->Form->button(__('<i class="fa fa-check"></i> Send', ['class' => 'comment_button']), $buttonOptions);
                        ?>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="message_alert_share pt-10">
                    <div class="alert alert-mini alert-danger">
                        <?= __('You must be logged in to comment.'); ?>
                        <?=
                        $this->Html->link(__('Sign up'), [
                            '_name' => 'register'
                                ], [
                            'class' => ' ',
                            'tabindex' => '-1',
                            'escape' => false
                                ]
                        );
                        ?> 
                        or
                        <?=
                        $this->Html->link(__('Login'), [
                            '_name' => 'login'
                                ], [
                            'class' => '',
                            'tabindex' => '-1',
                            'escape' => false
                                ]
                        );
                        ?>.
                    </div>
                </div>
            <?php endif; ?>
            </p>
        </div>
    </div>
</div>