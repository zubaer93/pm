
<footer id="footer" class="">
    <?php if (!$this->request->session()->read('Auth.User')): ?>
        <div class="container custom-content">
            <div class="row">
                <div class="col-lg-10 col-md-6 col-sm-6 col-xs-6 custom-style">
                    <?= $this->Html->image('_smarty/stockgitter_logo.png', ['class' => 'footer-logo mb-12 mt-10 center-block']); ?>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6 custom-style">
                
                    <div class="pull-right">
                        <p class="mb-10"><?= __('Follow Us'); ?></p>
                        <a href="https://www.facebook.com/StockGitter/" target="_blank" class="social-icon social-icon-sm social-icon-transparent social-facebook float-left" data-toggle="tooltip" data-placement="top" title="Facebook">
                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a>
                        <a href="https://twitter.com/stockgitter" target="_blank" class="social-icon social-icon-sm social-icon-transparent social-twitter float-left" data-toggle="tooltip" data-placement="top" title="Twitter">
                            <i class="icon-twitter"></i>
                            <i class="icon-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/stockgitter/" target="_blank" class="social-icon social-icon-sm social-icon-transparent social-instagram float-left" data-toggle="tooltip" data-placement="top" title="Instagram">
                            <i class="icon-instagram"></i>
                            <i class="icon-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="copyright">
        <div class="container">
            <ul class="list-inline inline-links mobile-block float-right m-0">
                <li>
                   <?= $this->Html->link(__('Home'), '/'); ?>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li>
                       <?= $this->Html->link(__($page->name), ['_name' => 'dynamic', 'name' => App\Helpers\SlugHelper::pageNameSlug($page->name)]); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            &copy; <?= __(' All Rights Reserved, StockGitter LTD'); ?>
        </div>
    </div>
</footer>
<?php if ($settings->enabled_penny): ?>
    <?php if (
        isset($this->request->params['pass'][0]) &&
        $this->request->params['pass'][0] == 'home' &&
        $this->request->params['controller'] == 'Pages' &&
        $this->request->params['action'] == 'display'
        ):
    ?>
        <div class="chatbot_div hide-mobile-chat" id="messages">
            <a href="javascript:;" class="chat-image">
                <?= $this->Html->image('_smarty/chat-image.png', ['class' => '']); ?>
            </a>
            <div class="chat">
                <div class="chat-header clearfix">
                    <?= $this->Html->image('_smarty/admin.jpg', ['class' => '']); ?>
                    <div class="chat-about">
                       <div class="chat-with">Chat Now!<br></div>
                       <div class="chat-num-messages"><button class="online-now">Online!</button></div>
                    </div>
                </div>
             
                <!-- end chat-header -->
                <div class="chat-history2">
                    <ul>
                    </ul>
                </div>
                <!-- end chat-history -->
                <div class="chat-message clearfix">
                    <span>Your Answer</span>
                    <input type="text" name="message-to-send" id="message-to-send2" placeholder="Type your message" />
                    <div id="loading_img2" class="loadin_image" style="display:none;">
                        <?= $this->Html->image('_smarty/loaders/5.gif', ['class' => 'img1234']); ?>    
                    </div>
                    <div  class="maali"></div>
                    <!--<button>Send</button>-->
                </div>
             <!-- end chat-message -->
            </div>

            <!-- end container -->
            <script id="message-template2" type="text/x-handlebars-template">
                <li class="clearfix">
                    <div class="message-data ">
                    </div>
                    <div class="message other-message float-right">
                        <span class="message-data-name" ><i class="fa fa-circle me"></i>You</span> 
                        <br>
                        &nbsp; &nbsp;&nbsp;{{messageOutput}}
                        <br>
                        <span class="message-data-time" >
                            <i class="fa fa-clock-o"></i>
                            &nbsp; &nbsp;{{time}}, Today
                        </span> &nbsp; &nbsp;
                    </div>
                </li>
            </script>
            <script id="message-response-template2" type="text/x-handlebars-template">
                <li>
                    <div class="message-data"></div>
                    <div class="message my-message">
                        <span class="message-data-name">
                            <i class="fa fa-circle online"></i>
                            Penny
                        </span>
                        <br>
                            &nbsp; &nbsp;&nbsp;{{response}}
                        <br>
                        <span class="message-data-time"><i class="fa fa-clock-o"></i>&nbsp; &nbsp;{{time}}, Today</span> &nbsp; &nbsp;
                    </div>
                </li>
            </script>
        </div>
    <?php else: ?>
        <div class="inner-page-chat">
            <div class="chatbot_div hide-mobile-chat" id="messages">
                <a href="javascript:;" class="chat-image">Chat with Penny</a>
                <div  class="container clearfix">
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <?= $this->Html->image('_smarty/admin.jpg', ['class' => '']); ?>
                            <div class="chat-about">
                                <div class="chat-with">Chat Now!<br></div>
                                <div class="chat-num-messages"><button class="online-now">Online!</button></div>
                            </div>
                        </div>
                    
                        <!-- end chat-header -->
                        <div class="chat-history2">
                            <ul></ul>
                        </div>
                    
                        <!-- end chat-history -->
                        <div class="chat-message clearfix">
                            <span>Your Answer</span>
                            <input type="text" name="message-to-send" id="message-to-send2" placeholder="Type your message" />
                            <div id="loading_img2" class="loadin_image" style="display:none;">
                                <?= $this->Html->image('_smarty/loaders/5.gif', ['class' => 'img1234']); ?>        
                            </div>
                            <div  class="maali"></div>
                        </div>
                        <!-- end chat-message -->
                    </div>
                    <!-- end chat -->
                </div>
                <!-- end container -->
                <script id="message-template2" type="text/x-handlebars-template">
                    <li class="clearfix">
                        <div class="message-data "></div>
                        <div class="message other-message float-right">
                            <span class="message-data-name">
                                <i class="fa fa-circle me"></i>
                                You
                            </span> 
                            <br>
                            &nbsp; &nbsp;&nbsp;{{messageOutput}}
                            <br>
                            <span class="message-data-time" ><i class="fa fa-clock-o"></i>&nbsp; &nbsp;{{time}}, Today</span> &nbsp; &nbsp;
                        </div>
                    </li>
                </script>
                <script id="message-response-template2" type="text/x-handlebars-template">
                    <li>
                        <div class="message-data"></div>
                        <div class="message my-message">
                            <span class="message-data-name">
                                <i class="fa fa-circle online"></i>
                                Penny
                            </span>
                            <br>
                            &nbsp; &nbsp;&nbsp;{{response}}
                            <br>
                            <span class="message-data-time">
                                <i class="fa fa-clock-o"></i>
                                &nbsp; &nbsp;{{time}}, Today
                            </span> &nbsp; &nbsp;
                        </div>
                    </li>
              </script>
           </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!--  Code on 20-04-20118 end   -->
<div class="information-ajax"
    data-mention-users-url="<?= $this->Url->build(['controller' => 'AppUsers', 'action' => 'getMentionUsers']); ?>"
    data-mention-companies-url="<?= $this->Url->build(['controller' => 'Companies', 'action' => 'getMentionSymbols']); ?>"
    data-symbol-url="<?= $this->Url->build(['controller' => 'Companies', 'action' => 'symbol']); ?>"
    data-forex-url="<?= $this->Url->build(['controller' => 'Forex', 'action' => 'symbol']); ?>"
    data-user-symbol-url="<?= $this->Url->build(['controller' => 'User']); ?>"
    data-read-notification-url="<?= $this->Url->build(['_name' => 'read_notification']); ?>">
</div>
<!--  Code on 02-04-20118      -->
<script src="//static.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>

<?php if ($settings->enabled_penny): ?>
    <script type="text/javascript">
        (function() {
            var newtext = '';
            var chat = {
                messageToSend: '',
                init: function() {
                    this.cacheDOM();
                    this.bindEvents();
                    this.render();
                },
                cacheDOM: function() {
                    this.$chatHistory = $('.chat-history');
                    this.$button = $('button');
                    this.$textarea = $('#message-to-send');
                    this.$chatHistoryList =  this.$chatHistory.find('ul');
                },
                bindEvents: function() {
                    this.$button.on('click', this.addMessage.bind(this));
                    this.$textarea.on('keyup', this.addMessageEnter.bind(this));
                },
                render: function() {
                    this.scrollToBottom();
                    if (this.messageToSend.trim() !== '') {
                        var template = Handlebars.compile( $("#message-template").html());
                        newtext = this.messageToSend;
                        var context = { 
                            messageOutput: this.messageToSend,
                            time: this.getCurrentTime()
                        };

                       this.$chatHistoryList.append(template(context));
                       this.scrollToBottom();
                       this.$textarea.val('');

                        var templateResponse = Handlebars.compile( $("#message-response-template").html());
                        var contextResponse = { 
                            response: this.getReponseFromApi(newtext),
                            time: this.getCurrentTime()
                        };

                        setTimeout(function() {
                            this.$chatHistoryList.append(templateResponse(contextResponse));
                            this.scrollToBottom();
                        }.bind(this), 500);
                    }
                },
                addMessage: function() {
                    this.messageToSend = this.$textarea.val()
                    this.render();         
                },
                addMessageEnter: function(event) {
                    if (event.keyCode === 13) {
                        this.addMessage();
                    }
                },
                scrollToBottom: function() { 
                    newmsgh = $('.chat-history ul li:last-child .my-message').outerHeight();
                    var oldheight = this.$chatHistory[0].scrollHeight - newmsgh - 40;
                    
                    if (newmsgh >= 250) {
                        this.$chatHistory.scrollTop(oldheight);// $('.chat-history ul li:last-child .my-message').css('po');
                    } else {
                        this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
                    }
                },
                getCurrentTime: function() {
                    return new Date().toLocaleTimeString().
                        replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                },
                getRandomItem: function(arr) {
                    return arr[Math.floor(Math.random()*arr.length)];
                },
                getReponseFromApi: function(newtext) {
                    var message = $.ajax({
                        type: "POST",
                        data : {value : newtext},
                        url: '<?= $this->Url->build(["controller" => "pages","action" => "api_response"])?>',
                        success: function(messageResponses) {
                            $('.loadin_image').hide();
                        },
                        async:false
                    });

                    return message.responseText ;
                },
            };

            chat.init();
        })();

        (function() {
            var newtext = '';
            var chat = {
                messageToSend: '',
                init: function() {
                    this.cacheDOM();
                    this.bindEvents();
                    this.render();
                },
                cacheDOM: function() {
                    this.$chatHistory = $('.chat-history2');
                    this.$button = $('button');
                    this.$textarea = $('#message-to-send2');
                    this.$chatHistoryList =  this.$chatHistory.find('ul');
                },
                bindEvents: function() {
                    this.$button.on('click', this.addMessage.bind(this));
                    this.$textarea.on('keyup', this.addMessageEnter.bind(this));
                },
                render: function() {
                    this.scrollToBottom();
                    if (this.messageToSend.trim() !== '') {
                        var template = Handlebars.compile( $("#message-template2").html());
                        newtext = this.messageToSend;
                        var context = {
                            messageOutput: this.messageToSend,
                            time: this.getCurrentTime()
                        };

                        this.$chatHistoryList.append(template(context));
                        this.scrollToBottom();
                        this.$textarea.val('');

                        var templateResponse = Handlebars.compile( $("#message-response-template2").html());
                        var contextResponse = {
                            response: this.getReponseFromApi(newtext),
                            time: this.getCurrentTime()
                        };

                        setTimeout(function() {
                            this.$chatHistoryList.append(templateResponse(contextResponse));
                            this.scrollToBottom();
                        }.bind(this), 500);
                    }
                },
                addMessage: function() {
                    this.messageToSend = this.$textarea.val()
                    this.render();
                },
                addMessageEnter: function(event) {
                    if (event.keyCode === 13) {
                        this.addMessage();
                    }
                },
                scrollToBottom: function() {
                    newmsgh = $('.chat-history2 ul li:last-child .my-message').outerHeight();
                    var oldheight = this.$chatHistory[0].scrollHeight - newmsgh - 40;
                    if (newmsgh >= 250) {
                        this.$chatHistory.scrollTop(oldheight);// $('.chat-history2 ul li:last-child .my-message').css('po');
                    } else {
                        this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
                    }
                },
                getCurrentTime: function() {
                   return new Date().toLocaleTimeString().
                        replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                },
                getRandomItem: function(arr) {
                    return arr[Math.floor(Math.random()*arr.length)];
                },
                getReponseFromApi: function(newtext) {
                    var message = $.ajax({
                        type: "POST",
                        data : {value : newtext},
                        url: '<?= $this->Url->build(["controller" => "pages","action" => "api_response"])?>',
                        success: function(messageResponses) {
                            $('.loadin_image').hide();
                        },
                        async:false
                    });
                    return message.responseText;
                },
            };

            chat.init();
        })();

        $('document').ready(function() {
            var li = firstMessage();
            $('.chat-history ul').append(li);
        });

        $('document').ready(function() {
            var li = firstMessage();
            $('.chat-history2 ul').append(li);
        });

        function firstMessage() {
            firstLi = '<li class="clearfix"><div class="message-data align-left"></div><div class="message my-message"><span class="message-data-name"><i class="fa fa-circle online"></i>Penny</span><br><div>Hi<?php if(!empty( $this->request->session()->read('Auth.User.first_name') )){ echo ' '.$this->request->session()->read('Auth.User.first_name');} ?>, I’m Penny, How may I help You?</div><br><span class="message-data-time"><i class="fa fa-clock-o"></i>&nbsp; &nbsp;<?php echo date("H:i"); ?>, Today</span> &nbsp; &nbsp;</div></li>';
            return firstLi;
        }

        $(document).ready(function() {   
            $('.chat-image').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
               $('.chat').toggle();
               $('.hide-mobile-chat .chat-image').toggle();
            });
           
            $('.chat').click(function(e) {   
               e.stopPropagation();
            });
            
            $('body').click(function() {  
                $('.chat').hide();
                $('.chat-image').show();
            });
        });
    </script>
<?php endif; ?>

<script>
    $(document).keypress(function(e) {
        var input_text_val = $('#message-to-send').val();
        if (input_text_val != "") {
            if (e.which == 13) {
                $('.loadin_image').show();
            }
        }
        
        var input_text_val2 = $('#message-to-send2').val();
        if (input_text_val2 != "") {
            if (e.which == 13) {
                $('.loadin_image').show();
            }
        }
    });
</script>