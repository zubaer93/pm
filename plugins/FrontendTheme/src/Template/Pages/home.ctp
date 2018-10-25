<div class="container text-center mt-p-3">
    <div class="heading-title  text-center mt-10 mb-20">
        <h1 class="m-0 fs-50 fw-300 wow bounceIn animated" data-wow-delay="0.4s"><span><?= __('LOOKING FOR SOMETHING?'); ?></span></h1>
    </div>
    <div>
        <?php if($subdomain):?>
        <?=
        $this->Html->link(__('Get more out of Stock Gitter Connect'), [
                '_name'=>'connect_page',
                'class' => ''
        ]);
        ?>
        <?php endif;?>
    </div>
 <div class="mt-30">
        <form role="search" method="get" id="searchform" class="revtp-searchform" action="#">
            <div class="row">
                <div class="col-md-6 col-sm-12 mb-10 search_div">
                    <div class="easy-autocomplete eac-square" style="width: 400px;">
                        <input type="text" name="s" id="js-search-home" placeholder="Enter symbol, company name, username" autocomplete="off">
                        <img class='js-search-home-loader' src="<?= $this->Url->build('frontend_theme/img/loading-small.gif') ?>"
                             alt="Loader" style="position: absolute;top: 13px;left: 360px;width: 30px;display: none;"
                             data-lang="<?php echo $currentLanguage;?>"
                        >
                        <div class="easy-autocomplete-container" id="eac-container-js-search-home">
                            <ul style="display: none;"></ul></div></div>  
                </div>
            </div>
        </form> 

    </div>
</div>
<hr>
<!-- WELCOME -->
<div class="mt-40">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="owl-carousel buttons-autohide controlls-over radius-4 owl-theme owl-carousel-init" data-plugin-options="{&quot;singleItem&quot;: true, &quot;autoPlay&quot;: false, &quot;navigation&quot;: true, &quot;pagination&quot;: false, &quot;transitionStyle&quot;:&quot;fade&quot;}" style="opacity: 1; display: block;">
                    <!-- item -->
                    <div class="owl-wrapper-outer" onclick="location.href = '/USD/dashboard';">
                        <div class="owl-wrapper">
                            <div class="owl-item">
                                <div class="caption-slider-default">
                                    <div class="display-table">
                                        <div class="display-table-cell">
                                            <div class="caption-container">
                                                <h2 class="bold font-raleway wow bounceInDown animated" data-wow-delay="0.5s">  
                                                    <?=
                                                    $this->Html->link(__('USA MARKET'), '/USD/dashboard', [
                                                        'class' => 'tab-post-link text-green_c text-white'
                                                    ]);
                                                    ?>    
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?=
                                $this->Html->link($this->Html->image('home/usa_market.jpeg', [
                                            'width' => '851',
                                            'img-fluid radius-4' => '335',
                                            'class' => 'img-fluid radius-4 height_311  wow bounceInLeft animation-visible animated',
                                        ]), '/USD/dashboard', ['escape' => false]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="owl-carousel buttons-autohide controlls-over radius-4 owl-theme owl-carousel-init" data-plugin-options="{&quot;singleItem&quot;: true, &quot;autoPlay&quot;: false, &quot;navigation&quot;: true, &quot;pagination&quot;: false, &quot;transitionStyle&quot;:&quot;fade&quot;}" style="opacity: 1; display: block;">
                    <!-- item -->
                    <div class="owl-wrapper-outer" onclick="location.href = '/JMD/dashboard';">
                        <div class="owl-wrapper">
                            <div class="owl-item">
                                <div class="caption-slider-default">
                                    <div class="display-table">
                                        <div class="display-table-cell">
                                            <div class="caption-container">
                                                <h2 class="bold font-raleway wow bounceInDown animated" data-wow-delay="0.5s">  
                                                    <?=
                                                    $this->Html->link(__('JAMAICAN MARKET'), '/JMD/dashboard', [
                                                        'class' => 'tab-post-link text-green_c text-white'
                                                    ]);
                                                    ?>   
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?=
                                $this->Html->link($this->Html->image('home/jamaican_market.jpeg', [
                                            'width' => '851',
                                            'height' => '311',
                                            'img-fluid radius-4' => '335',
                                            'class' => 'img-fluid radius-4 height_311 wow bounceInRight animation-visible animated',
                                        ]), '/JMD/dashboard', ['escape' => false]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container mt-30 mb-20">
        <div class="row">
            <!-- recent news USA -->
            <div class="col-md-6 col-sm-6">
                <div class="box-messages mb-30">
                    <h4><?= __('Latest on the US Market'); ?></h4>
                    <hr>

                    <?= $this->element('News/news'); ?>
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More News...'), '/USD/news', [
                        'tabindex' => '-1',
                        'escape' => false,
                        'class' => 'btn btn-sm btn-reveal btn-default float-right'
                            ]
                    );
                    ?>
                </div>
            </div>
            <!-- /recent news -->

            <!-- recent news Jamaican  -->
            <div class="col-md-6 col-sm-6">
                <div class="box-messages mb-30">
                    <h4><?= __('Latest on the Jamaican Market'); ?></h4>
                    <hr>

                    <?= $this->element('News/news_jamaican'); ?>
                    <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More News...'), '/JMD/news', [
                        'tabindex' => '-1',
                        'escape' => false,
                        'class' => 'btn btn-sm btn-reveal btn-default float-right'
                            ]
                    );
                    ?>
                </div>
            </div>
            <!-- /recent news -->

        </div>

    </div>
</div>