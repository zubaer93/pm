<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->Html->charset('utf-8'); ?>
        <title>Admin</title>
        <meta name="description" content="" />
        <meta name="Author" content="Dorin Grigoras [www.stepofweb.com]" />
        <?= $this->Html->meta('favicon.ico', '/assets/images/favicon.ico', array('type' => 'icon')); ?>

        <!-- mobile settings -->
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

        <!-- WEB FONTS -->
        <?= $this->Html->css('https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext'); ?>

        <!-- CORE CSS -->
        <?= $this->Html->css('/assets/plugins/bootstrap/css/bootstrap'); ?>

        <!-- THEME CSS -->
        <?= $this->Html->css('/assets/css/essentials'); ?>
        <?= $this->Html->css('/assets/css/layout'); ?>
        <?= $this->Html->css('/assets/css/color_scheme/green'); ?>
        <?= $this->Html->css('/assets/css/layout-datatables'); ?>
        <?= $this->Html->script('/assets/js/plugins/jquery/jquery-2.2.3.min'); ?>

        <script type="text/javascript">var plugin_path = '/admin_theme/assets/plugins/';</script>
        <?= $this->Html->script('/assets/js/app'); ?>

        <?= $this->fetch('css');?>
    </head>
    <body>

