<div id="trending-container" class="clearfix classeteste">
    <div class="divs_ticker">
        <div class="row ticker_row">
            <div class="col-lg-2 col-md-6 col-sm-6 first_div_ticker">
                <h4 class="ticker-h4"><?= __('Stock Activity:'); ?></h4>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 third_div_ticker">
                <?=
                $this->Html->link(
                        $this->Html->tag('i', '', ['class' => 'fa fa-list']) . __('ALL ' . $currentLanguage . ' Stocks'), [
                    '_name' => 'stocks_list'
                        ], [
                    'class' => 'btn btn-primary btn-sm ticker-button',
                    'tabindex' => '-1',
                    'escape' => false
                        ]
                );
                ?>
                <!--  code on 20-04 start -->

                <div class="show-mobile-chat">
                    <div class="chatbot_div" id="messages">
                        <a href="javascript:;" class="chat-image">
                            <?= $this->Html->image('_smarty/admin.jpg', ['class' => '']); ?>
                        </a>
                        <div  class="container clearfix">
                            <div class="chat">
                                <div class="chat-header clearfix">
                                    <?= $this->Html->image('_smarty/admin.jpg', ['class' => '']); ?>

                                    <div class="chat-about">
                                        <div class="chat-with">Chat Now !<br></div>
                                        <div class="chat-num-messages"><button class="online-now">Online!</button></div>
                                    </div>

                                </div> <!-- end chat-header -->

                                <div class="chat-history">
                                    <ul>


                                    </ul>

                                </div> <!-- end chat-history -->

                                <div class="chat-message clearfix">
                                    <span>Your Answer</span>
                                    <input type="text" name="message-to-send" id="message-to-send" placeholder="Type your message" />
                                    <div id="loading_img" class="loadin_image" style="display:none;"><?= $this->Html->image('_smarty/loaders/5.gif', ['class' => 'img1234']); ?></div>
                                    <div  class="maali"></div>
                                    <!--<button>Send</button>-->
                                </div> <!-- end chat-message -->

                            </div> <!-- end chat -->

                        </div> <!-- end container -->

                        <script id="message-template" type="text/x-handlebars-template">
                            <li class="clearfix">
                            <div class="message-data ">


                            </div>
                            <div class="message other-message float-right">
                            <span class="message-data-name" ><i class="fa fa-circle me"></i>You</span> 
                            <br>
                            &nbsp; &nbsp;&nbsp;{{messageOutput}}
                            <br>
                            <span class="message-data-time" ><i class="fa fa-clock-o"></i>&nbsp; &nbsp;{{time}}, Today</span> &nbsp; &nbsp;
                            </div>
                            </li>
                        </script>

                        <script id="message-response-template" type="text/x-handlebars-template">
                            <li>
                            <div class="message-data">


                            </div>
                            <div class="message my-message">
                            <span class="message-data-name"><i class="fa fa-circle online"></i>Penny</span>
                            <br>
                            &nbsp; &nbsp;&nbsp;{{response}}
                            <br>
                            <span class="message-data-time"><i class="fa fa-clock-o"></i>&nbsp; &nbsp;{{time}}, Today</span> &nbsp; &nbsp;
                            </div>
                            </li>
                        </script>

                    </div>
                </div>

                <!--  code on 20-04 end-->


            </div>
         
        </div>
    </div>
</div>