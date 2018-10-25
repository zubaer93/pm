<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <?php 
            if (isset($companyInfo->symbol)) {
             $titel = $companyInfo->symbol . '-' . $companyInfo->name . ' Stockgitter ' . $currentLanguage;
            } else {
                $titel = 'Stockgitter ' . $currentLanguage;
            }
        ?>
        <title><?= $titel; ?></title>
        <meta name="description" content="<?= __('Dorin Grigoras [www.stepofweb.com]'); ?>" />
        <meta name="Author" content="<?= __('StockGitter is an international financial connection platform that gathers interests from the general public about different financial products and connects users and institutions.'); ?>" />
        <meta property="og:title" content="<?= __('Stockgitter'); ?>" />
        <meta property="og:description" content="<?= __('StockGitter is an international financial connection platform that gathers interests from the general public about different financial products and connects users and institutions.'); ?>" />
        <?= $this->Html->meta('favicon.ico', 'img/favicon.ico', array('type' => 'icon')); ?>
        <!-- mobile settings -->
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

        <!-- WEB FONTS : use %7C instead of | (pipe) -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600%7CRaleway:300,400,500,600,700%7CLato:300,400,400italic,600,700" rel="stylesheet" type="text/css" />

        <!-- CORE CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <?= $this->Html->css('FrontendTheme.../css/symbol/symbol'); ?>

        <?= $this->Html->css('FrontendTheme.../css/search'); ?>

        <?= $this->element('CSS/custom'); ?>

        <?= $this->Html->css('FrontendTheme.../plugins/bootstrap/css/bootstrap'); ?>

        <!-- REVOLUTION SLIDER -->
        <?= $this->Html->css('FrontendTheme.../plugins/slider.revolution.v5/css/pack'); ?>

        <!-- QTIP -->
        <?= $this->Html->css('FrontendTheme.../plugins/qtip/jquery.qtip.min'); ?>

        <!-- AUTOCOMPLETE -->
        <?= $this->Html->css('FrontendTheme.../plugins/easy-autocomplete/easy-autocomplete.min'); ?>
        <?= $this->Html->css('FrontendTheme.../plugins/easy-autocomplete/easy-autocomplete.themes'); ?>

        <!-- THEME CSS -->
        <?= $this->Html->css('FrontendTheme.essentials'); ?>
        <?= $this->Html->css('FrontendTheme.layout'); ?>
        <?= $this->Html->css('FrontendTheme.thematics-restaurant'); ?>

        <!-- PAGE LEVEL SCRIPTS -->
        <?= $this->Html->css('FrontendTheme.header-1'); ?>

        <?php if ($settings->enabled_penny): ?>
            <!--  code on 20-04-2018 start-->
            <?= $this->Html->css('FrontendTheme.chatbot'); ?>
            <!--  code on 20-04-2018 end-->
        <?php endif; ?>

        <?= $this->Html->css('FrontendTheme.color_scheme/green'); ?>

        <?= $this->Html->css('FrontendTheme.tickers/script'); ?>
        <?= $this->Html->css('FrontendTheme.site'); ?>
        <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/jquery/jquery-3.2.1.min'); ?>
        <?= $this->fetch('css'); ?>
    </head>

    <body class="smoothscroll enable-animation">

        <!-- SLIDE TOP -->
        <?= $this->element('Home/slidetop'); ?>
        <!-- /SLIDE TOP -->

        <!-- wrapper -->
        <div id="wrapper">

            <!-- Top Bar -->
            <?= $this->element('Layout/topbar'); ?>
            <!-- /Top Bar -->

            <!-- Modal-->
            <?= $this->element('Layout/modal'); ?>
            <!-- /Modal -->

            <!-- Nav Bar -->
            <?= $this->element('Layout/navbar'); ?>
            <!-- /Nav Bar -->

            <!-- TICKERS -->
            <?= $this->element('Layout/tickers', [$trendingCompanies]); ?>
            <!-- /TICKERS -->
            <div class="res-content mt-10">
            <?= $this->fetch('content'); ?>
            </div>
            <!-- FOOTER -->
            <?= $this->element('Layout/footer'); ?>
            <!-- /FOOTER -->

        </div>
        <!-- /wrapper -->

        <!-- SCROLL TO TOP -->
        <a href="#" id="toTop"></a>

        <!-- PRELOADER -->
        <div id="preloader">
            <div class="inner">
                <span class="loader"></span>
            </div>
        </div><!-- /PRELOADER -->

        <!-- JAVASCRIPT FILES -->
        <script type="text/javascript">var plugin_path = '/frontend_theme/plugins/';</script>
        <script type="text/javascript">var notification = '<?= $this->Url->build(['_name' => 'get_notification']); ?>';
        </script>

        <?= $this->element('mustache'); ?>
        <?= $this->Html->script('https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js'); ?>

        <?= $this->Html->script('FrontendTheme.scripts'); ?>

        <!-- MUSTACHE JS FILES -->
        <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.js'); ?>

        <!-- AUTOCOMPLETE JS FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/easy-autocomplete/jquery.easy-autocomplete'); ?>

        <!-- QTIP FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/qtip/jquery.qtip.min'); ?>

        <!-- MENTION JS FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/mention/mention'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/mention/bootstrap-typeahead'); ?>

        <!-- REVOLUTION SLIDER -->
        <?= $this->Html->script('FrontendTheme.../plugins/slider.revolution.v5/js/jquery.themepunch.tools.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/slider.revolution.v5/js/jquery.themepunch.revolution.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/form.validate/jquery.form.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/editor.summernote/summernote.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/editor.markdown/js/bootstrap-markdown.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/form.validate/jquery.validation.min'); ?>
        <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js'); ?>

        <!-- STYLESWITCHER - REMOVE -->
        <?= $this->Html->script('FrontendTheme.styleswitcher'); ?>

        <?= $this->Html->script('FrontendTheme.../plugins/bootstrap/js/bootstrap.min'); ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if (jQuery("#rev_slider_98_1").revolution == undefined) {
                    revslider_showDoubleJqueryError("#rev_slider_98_1");
                } else {
                    revapi98 = jQuery("#rev_slider_98_1").show().revolution({
                        sliderType: "hero",
                        jsFileLocation: plugin_path + "slider.revolution.v5/js/",
                        sliderLayout: "fullwidth",
                        dottedOverlay: "none",
                        delay: 9000,
                        navigation: {
                        },
                        responsiveLevels: [1240, 1024, 778, 480],
                        gridwidth: [1240, 1024, 778, 480],
                        gridheight: [600, 500, 400, 300],
                        lazyType: "none",
                        parallax: {
                            type: "mouse",
                            origo: "slidercenter",
                            speed: 2000,
                            levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50],
                        },
                        shadow: 0,
                        spinner: "off",
                        autoHeight: "off",
                        disableProgressBar: "on",
                        hideThumbsOnMobile: "off",
                        hideSliderAtLimit: 0,
                        hideCaptionAtLimit: 0,
                        hideAllCaptionAtLilmit: 0,
                        debugMode: false,
                        fallbacks: {
                            simplifyAll: "off",
                            disableFocusListener: false,
                        }
                    });
                }
            });
        </script>

        <div class="modal fade bs-subscription-modal-full" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-full">
                <div class="modal-content">

                    <!-- header modal -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">Subscriptions</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>

                    <!-- body modal -->
                    <div class="modal-body">
                        <?= $this->element('Users/subscribe'); ?>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Page scripts dependecies -->
        <?= $this->fetch('script'); ?>
        <?= $this->fetch('scriptBottom'); ?>

    </body>
</html>
