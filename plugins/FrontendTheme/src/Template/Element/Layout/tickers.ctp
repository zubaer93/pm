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
            <div class="col-lg-8 col-md-12 col-sm-12 second_div_ticker">
                <ul id="trending-list" class="top-links list-inline">
                    <?php foreach ($trendingCompanies as $key => $company):?>
                        <li title="<?= $company['company']['name']; ?>">
                            <a href="<?= $this->Url->build(['_name' => 'symbol', 'stock' => $company['symbol']]); ?>">
                                <h5 class="name selectable ">
                                    <span class="stock-link"><?= $company['symbol']; ?></span>
                                    <span class="price-change worse
                                          <?= (($company['open'] - $company['close']) >= 0) ? 'positive' : 'negative'; ?>">
                                          <?php
                                              $percentage = $company['open'] == 0 ? 0 :
                                                      ($company['open']- $company['close']) * 100 / $company['open'];
                                              ?>
                                              <?=
                                              $this->Number->currency($company['open']) . ' ('
                                              . $this->Number->toPercentage($percentage) . ')';
                                              ?>
                                    </span>
                                </h5>
                            </a>
                            <div class="title-cells">
                                <a href="<?= $this->Url->build(['_name' => 'symbol', 'stock' => $company['symbol']]); ?>">
                                    <div class="cell">
                                        <span class="price">
                                            <?= __('Vol: ') . $company['volume']; ?>
                                        </span>
                                        <span class="percent-change"></span>
                                        <time class="market-hours timestamp published-at">
                                            <?= __('real time:') . ' ' . $company['last_refreshed']; ?>
                                        </time>
                                    </div>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>