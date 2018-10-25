<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>StockGitter coming soon</title>
        <meta name="description" content="" />
        <meta name="Author" content="Dorin Grigoras [www.stepofweb.com]" />

        <!-- mobile settings -->
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

        <!-- WEB FONTS : use %7C instead of | (pipe) -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600%7CRaleway:300,400,500,600,700%7CLato:300,400,400italic,600,700" rel="stylesheet" type="text/css" />

        <!-- CORE CSS -->
        <?= $this->Html->css('FrontendTheme.../css/symbol/symbol'); ?>

        <?= $this->Html->css('FrontendTheme.../css/search'); ?>

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
        <?= $this->Html->css('FrontendTheme.color_scheme/green'); ?>

        <?= $this->Html->css('FrontendTheme.tickers/script'); ?>

        <?= $this->fetch('css'); ?>
    </head>

    <!--
        AVAILABLE BODY CLASSES:

        smoothscroll            = create a browser smooth scroll
        enable-animation        = enable WOW animations

        bg-grey                 = grey background
        grain-grey              = grey grain background
        grain-blue              = blue grain background
        grain-green             = green grain background
        grain-blue              = blue grain background
        grain-orange            = orange grain background
        grain-yellow            = yellow grain background

        boxed                   = boxed layout
        pattern1 ... patern11   = pattern background
        menu-vertical-hide      = hidden, open on click

        BACKGROUND IMAGE [together with .boxed class]
        data-background="assets/images/_smarty/boxed_background/1.jpg"
    -->
    <body class="smoothscroll enable-animation boxed">

        <!-- wrapper -->
        <div id="wrapper">

            <?= $this->fetch('content'); ?>

        </div>

        <!-- JAVASCRIPT FILES -->
        <script type="text/javascript">var plugin_path = '/frontend_theme/plugins/';</script>

        <?= $this->Html->script('FrontendTheme.../plugins/jquery/jquery-3.2.1.min'); ?>

        <?= $this->Html->script('FrontendTheme.scripts'); ?>

        <!-- MUSTACHE JS FILES -->
        <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.js'); ?>

        <!-- AUTOCOMPLETE JS FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/easy-autocomplete/jquery.easy-autocomplete'); ?>

        <!-- HIGHSTOCK FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/highstock/highstock'); ?>

        <!-- QTIP FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/qtip/jquery.qtip.min'); ?>

        <!-- MENTION JS FILES -->
        <?= $this->Html->script('FrontendTheme.../plugins/mention/bootstrap-typeahead'); ?>

        <!-- REVOLUTION SLIDER -->
        <?= $this->Html->script('FrontendTheme.../plugins/slider.revolution.v5/js/jquery.themepunch.tools.min'); ?>
        <?= $this->Html->script('FrontendTheme.../plugins/slider.revolution.v5/js/jquery.themepunch.revolution.min'); ?>

        <!-- STYLESWITCHER - REMOVE -->
        <?= $this->Html->script('FrontendTheme.styleswitcher'); ?>

        <?= $this->Html->script('FrontendTheme.../plugins/bootstrap/js/bootstrap.min')?>

        <!-- Page scripts dependecies -->
        <?= $this->fetch('script'); ?>

    </body>
</html>
