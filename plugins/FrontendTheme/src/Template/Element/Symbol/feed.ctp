<?php $this->Html->script(['messages/message.js'], ['block' => 'script']);?>
<?php
$this->Html->script(
        [
    'toggle.js'
        ], ['block' => 'script']
);
?>
<?= $this->Html->css('FrontendTheme.../link_hover/css/livepreview-demo'); ?>

<?php if (!isset($page_is)): ?>
    <?php $page_is = null; ?>
<?php endif; ?>

<?php if (!isset($page_data)): ?>
    <?php $page_data = null; ?>
<?php endif; ?>

<?php if (!isset($avatarPath)): ?>
    <?php $avatarPath = null; ?>
<?php endif; ?>

<div class="feed-container toastr-notify" data-progressBar="true" data-position="top-right" data-notifyType="success" data-message="Successfully Saved!" data-url-img="<?= $avatarPath ?>" 
    data-grade-url="<?= $this->Url->build(['_name' => 'add_reting']); ?>"
    data-post-comments-url="<?= $this->Url->build(['_name' => 'get_post_comments']); ?>"
    data-delete-post="<?= $this->Url->build(['_name' => 'delete_post_user']); ?>"
    data-iframe-link="<?= $this->Url->build(['_name' => 'iframe_link']); ?>"
    data-modal-url="<?= $this->Url->build(['_name' => 'getMessageInfo']); ?>"
    data-show-chart="<?= (($currentLanguage == 'JMD' || !isset($companyInfo['id'])) ? 'false' : 'true'); ?>"
    data-screenshot="<?= $this->Url->build(['_name' => 'iframe_link']); ?>"
    data-refresh="<?= $this->Url->build(['_name' => 'refresh_messages']); ?>"
    data-refresh-comment="<?= $this->Url->build(['_name' => 'refresh_comment']); ?>"
    data-count-comment="<?= $this->Url->build(['_name' => 'count_comment']); ?>"
    data-follow-url="<?= $this->Url->build(['_name' => 'follow_user']); ?>"
    data-what-a-page="<?= $page_is; ?>"
    data-what-a-page-data="<?= $page_data; ?>">
    <div class="box-message">
        <div class="box-light"><!-- .box-light OR .box-dark -->
            <?php
                $formOptions = [
                    'id' => 'formMessage',
                    'url' => ['controller' => 'Messages', 'action' => 'post']
                ];
                echo $this->Form->create('Newsfeed', $formOptions);
            ?>

            <div class="box-inner">
                <?php
                    $config = [
                        'id' => 'message',
                        'class' => 'message-box form-control h-100',
                        'placeholder' => 'Share an idea (use ! before ticker: e.g !SYMBL)',
                        'data-maxlength' => '200',
                        'onkeyup' => 'countChar(this)'
                    ];
                    echo $this->Form->textarea('message', $config);
                ?>

                <div class="chart-container">
                    <a class="mb3-btn mb3-upload" href="javascript:void(0)" data-hasqtip="true">Chart</a>
                </div>

                <div class="text-muted text-right mt-3 fs-12">
                    <span class="words-to-write">200</span>
                </div>
                <div id="mydiv_iframe" style="display: none;height:220px;width:100%">

                </div>

                <?= $this->Form->hidden('user_id', ['type' => 'text', 'value' => $userId]); ?>
                <?= $this->Form->hidden('company_id', ['type' => 'text', 'value' => (isset($companyInfo['id'])) ? $companyInfo['id'] : '']); ?>
                <?= $this->Form->hidden('trader_id', ['type' => 'text', 'value' => (isset($exchangeInfo['id'])) ? $exchangeInfo['id'] : '']); ?>
                <?= $this->Form->hidden('market', ['type' => 'text', 'value' => $currentLanguage]); ?>
                <?= $this->Form->hidden('private_id', ['type' => 'text', 'value' => (isset($private_id)) ? $private_id : '']); ?>

                <?php
                    $buttonOptions = [
                        'type' => 'submit',
                        'escape' => false,
                        'class' => 'btn btn-primary btn-send-message btn-sm'
                    ];
                    echo $this->Form->button(__('<i class="fa fa-check"></i> SHARE', ['class' => 'share_button']), $buttonOptions);
                ?>

                <label class="btn btn-primary btn-send-message btn-sm pull-right">
                    <i class="fa fa-file"></i> Attach <input type="file" name="attach" style="display: none;">
                </label>
                <?= $this->Form->end() ?>

                <!-- ALERT -->
                <div class="message_alert"></div>
                <!-- /ALERT -->
            </div>

            <div class="box-inner">

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#posts" role="tab" data-toggle="tab">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#uploads" role="tab" data-toggle="tab">Uploads</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#references" role="tab" data-toggle="tab">Penny's Messages</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active show" id="posts">
                        <div class="row">

                            <!-- POPULAR POSTS -->
                            <div class="col-md-12 col-sm-12">

                                <div class="box-inner posts">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-7 col-sm-7">
                                                <?= __('POPULAR POSTS') ?>
                                            </div>
                                            <div class="col-lg-4 col-md-5 col-sm-5">
                                                <?php if (isset($rating)): ?>
                                                    <?= $this->element('Users/rating', ['rating' => $rating]); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="message_alert_share pt-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3></h3>
                                    <div class="slimScrollDiv" style="position: relative;  width: auto; ">
                                        <div class="slimscroll feed-list <?= (isset($custom_class) ? $custom_class : ''); ?>" data-always-visible="true" data-size="5px"
                                             data-position="right" data-opacity="0.4" disable-body-scroll="true" style="">
                                            <div class="box-messages">
                                                <ol id="first-stream-box" class="stream-list ol-list">
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /POPULAR POSTS -->
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="uploads">

                        <div class="row">

                            <!-- POPULAR POSTS -->
                            <div class="col-md-12 col-sm-12">

                                <div class="box-inner posts">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-7 col-sm-7">
                                                <?= __('POST UPLOADS') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <h3></h3>
                                    <div class="slimScrollDiv" style="position: relative;  width: auto; ">
                                        <div class="slimscroll feed-list <?= (isset($custom_class) ? $custom_class : ''); ?>" data-always-visible="true" data-size="5px"
                                             data-position="right" data-opacity="0.4" disable-body-scroll="true" style="">
                                            <div class="box-messages">
                                                <ol id="second-stream-box" class="stream-list ol-list">
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /POPULAR POSTS -->
                        </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="references"></div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Share Modal -->
<?= $this->element('Symbol/modal/share'); ?>
<?= $this->element('Symbol/modal/delete_share'); ?>
<!-- /Share Modal -->
