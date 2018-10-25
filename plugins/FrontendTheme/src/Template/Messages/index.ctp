<?php
// Load page scripts dependencies.
$this->Html->script(
        [
    'messages/message.js',
        ], [
    'block' => 'script'
        ]
);
?>

<style type="text/css">
    ol li{
        list-style-type: none;
    }
    ol li:hover{
        background-color: #f9f9f9;
    }
    hr{
        margin: 2px;
    }
    .message-box{
        height: 100px;
        width: 100%;
        background: #fff;
        border: 1px solid #a0a0a0;
        border-color: #a0a0a0 #a0a0a0 #cfcfcf;
        border-radius: 4px;
        box-shadow: 0 1px 0 #f6f6f6, inset 1px 1px 1px #dfdfdf;
        display: block;
        font: 14px/18px Arial, Trebuchet, sans-serif;
        overflow-y: hidden;
        padding: 6px 0 0 8px;
        resize: none;
    }
    .stream-list {
        list-style-type: none;
    }
    .stream-list > li .stream-content {
        padding: 9px 0px;
    }
    .mb3-btn {
        /*background: url(/../plugins/FrontendTheme/src/webroot/img/sprite-box.png) no-repeat;*/
        bottom: 8px;
        display: block;
        height: 22px;
        position: absolute;
        text-indent: -9999px;
        width: 22px;
    }
</style>

<section class="page-header page-header-xs">
    <div class="container">
        <!-- breadcrumbs -->
        <ol class="breadcrumb breadcrumb-inverse">
            <li><a href="#"><?= __('Home'); ?></a></li>
            <li><a href="#"><?= __('Pages'); ?></a></li>
            <li class="active"><?= __('Felicia Doe'); ?></li>
        </ol><!-- /breadcrumbs -->
    </div>
</section>

<!-- -->
<section>
    <div class="container-fluid">
        <div class="row">

            <?= $this->element('Users/sidenav'); ?>

            <div class="col-lg-6 col-md-6 col-sm-5 mb-80">

                <div class="box-light">
                    <div class="feed-container">
                        <div class="box-message">
                            <?php
                            $formOptions = [
                                'id' => 'formMessage',
                                'class' => 'box-light mt-20',
                                'url' => ['controller' => 'Messages', 'action' => 'post']
                            ];

                            echo $this->Form->create($newsfeed, $formOptions);
                            ?>
                            <div class="box-inner">

                                <h4 class="uppercase"><?= __('SHARE AN IDEIA'); ?></h4>

                                <div class="mb-container">
                                    <div class="mb-carat"></div>
                                    <div class="mb-textarea">
                                        <?php
                                        $config = [
                                            'id' => 'message',
                                            'class' => 'word-count message-box',
                                            'placeholder' => 'Share an ideia (use @ before ticker: e.g @SYMBL)',
                                            'data-maxlength' => '100',
                                            'onkeyup' => 'countChar(this)'
                                        ];
                                        echo $this->Form->textarea('message', $config);
                                        ?>
                                    </div>
                                </div>

                                <div class="chart-container">
                                    <a class="mb3-btn mb3-upload" href="javascript:void(0)" data-hasqtip="true"><?= __('Chart'); ?></a>
                                </div>

                                <div class="text-muted text-right mt-3 fs-12 mb-10">
                                    <span class="words-to-write">140</span>
                                </div>

                                <?php
                                $buttonOptions = [
                                    'type' => 'submit',
                                    'escape' => false,
                                    'class' => 'btn btn-primary btn-send-message'
                                ];
                                echo $this->Form->button(__('<i class="fa fa-check"></i> SHARE'), $buttonOptions);
                                ?>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>

                        <hr>

                        <div class="box-messages">
                            <ol class="stream-list">
                                <li class="messageli">
                                    <div class="stream-content">
                                        <div class="message">
                                            <div class="message-header">
                                                <div class="avatar"></div>
                                                <div class="message-date">Sep. 1 at 3:29 PM</div>
                                                <a href="#" class="message-username"><b>Bernardo Casanova</b></a>
                                            </div>
                                            <div class="message-content">
                                                <div class="message-body">
                                                    $JKS Successful test of the 200-week sma raises the odds for a test of AT highs in the coming weeks.
                                                </div>
                                                <div class="message-tools"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <hr>
                                <li class="messageli">
                                    <div class="stream-content">
                                        <div class="message">
                                            <div class="message-header">
                                                <div class="avatar"></div>
                                                <div class="message-date">Sep. 1 at 3:29 PM</div>
                                                <a href="#" class="message-username"><b>Bernardo Casanova</b></a>
                                            </div>
                                            <div class="message-content">
                                                <div class="message-body">
                                                    $JKS Successful test of the 200-week sma raises the odds for a test of AT highs in the coming weeks.
                                                </div>
                                                <div class="message-tools"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</section>

