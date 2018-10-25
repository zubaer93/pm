<!-- wrapper -->
<div id="wrapper">

<!-- HEADER -->
<div id="header" class="navbar-toggleable-md sticky transparent clearfix">
    <!-- TOP NAV -->
    <header id="topNav">
        <div class="container"><!-- add .full-container for fullwidth -->
            <ul class="float-right list-inline mobile-block list-social-icons mt-30 hidden-xs-down">
                <li>
                    <a href="https://www.facebook.com/StockGitter/" class="social-icon social-icon-transparent social-icon-sm social-facebook float-left" data-toggle="tooltip" data-placement="bottom" title="Facebook" target="_blank">
                        <i class="icon-facebook"></i>
                        <i class="icon-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/stockgitter" class="social-icon social-icon-transparent social-icon-sm social-twitter float-left" data-toggle="tooltip" data-placement="bottom" title="Twitter" target="_blank">
                        <i class="icon-twitter"></i>
                        <i class="icon-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/stockgitter" class="social-icon social-icon-transparent social-icon-sm social-instagram float-left" data-toggle="tooltip" data-placement="bottom" title="Instagram" target="_blank">
                        <i class="icon-instagram"></i>
                        <i class="icon-instagram"></i>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="modal" data-target="#contactModal" class="social-icon social-icon-transparent social-icon-sm float-left" data-placement="bottom" title="Contact Us">
                        <i class="et-envelope"></i>
                        <i class="et-envelope"></i>
                    </a>
                </li>
            </ul>
            <!-- Logo -->
            <a class="logo float-left" href="https://www.facebook.com/StockGitter/" target="_blank">
                <?= $this->Html->image('logo/stockgitter_logo.png'); ?>
            </a>
        </div>
    </header>
</div>
<!-- /HEADER -->

<!-- -->
<?php $image = $this->Url->image('1200x800/18-min.jpg'); ?>
<section id="slider" class="fullheight" style="background:url('<?= $image; ?>'); height: 503px;">
    <div class="overlay dark-5"><!-- dark overlay [0 to 9 opacity] --></div>
    <div class="display-table">
        <div class="display-table-cell vertical-align-middle">
            <div class="container text-center">

                <h1 class="mb-20 fs-40 mt-80"><b>STOCKGITTER IS UNDER CONSTRUCTION</b></h1>
                <p class="fs-20 font-lato text-muted"><b>Please, check back again , we are working hard so check us out on our social media!</b></p>

                <div style="max-width:550px; margin:auto; m-top:60px; m-bottom:80px;">
                    <div class="countdown squared dark theme-style" data-labels="years,months,weeks,days,hour,min,sec" data-from="November 30, 2017 23:00:00"></div>
                </div>
                <div style="max-width:500px; margin:auto;">
                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'contacts',
                            'action' => 'send'
                        ],
                        'data-toastr-position' => 'top-right'
                    ]);?>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <?= $this->Form->control('email', [
                                'type' => 'email',
                                'class' => 'form-control',
                                'placeholder' => __('Enter your Email'),
                                'label' => false,
                                'templates' => [
                                    'inputContainer' => '{{content}}'
                                ],
                                'required' => true
                            ]);?>
                            <span class="input-group-btn">
                                <?= $this->Form->button(__('Subscribe'), ['class' => 'btn btn-success']); ?>
                            </span>
                        </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- / -->

    <!-- MODAL -->
    <div id="contactModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">CONTACT US</h4>
                </div>

                <!-- AJAX CONTACT FORM USING VALIDATE PLUGIN -->
                <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'contacts',
                            'action' => 'sendModal'
                        ]
                    ]);?>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="contact:name">Full Name *</label>
                                    <?= $this->Form->control('name', [
                                        'class' => 'form-control',
                                        'label' => false,
                                        'required' => true,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact:email">E-mail Address *</label>
                                    <?= $this->Form->control('email', [
                                        'type' => 'email',
                                        'class' => 'form-control',
                                        'label' => false,
                                        'required' => true,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact:phone">Phone</label>
                                    <?= $this->Form->control('phone', [
                                        'class' => 'form-control',
                                        'label' => false,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="contact:subject">Subject *</label>
                                    <?= $this->Form->control('subject', [
                                        'class' => 'form-control',
                                        'label' => false,
                                        'required' => true,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact_department">Department</label>
                                    <?= $this->Form->control('department', [
                                        'options' => $departments,
                                        'class' => 'form-control',
                                        'label' => false,
                                        'required' => true,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="contact:message">Message *</label>
                                    <?= $this->Form->control('message', [
                                        'type' => 'textarea',
                                        'class' => 'form-control',
                                        'label' => false,
                                        'required' => true,
                                        'maxlength' => '10000',
                                        'rows' => 8,
                                        'templates' => [
                                            'inputContainer' => '{{content}}'
                                        ]
                                    ]);?>
                                </div>
                            </div>

                        </fieldset>

                        <div class="row">
                            <div class="col-md-12">
                                
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary float-left"><i class="fa fa-check"></i> SEND MESSAGE</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- /MODAL -->
</div>
<!-- /wrapper -->


<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-109016216-1', 'auto');
    ga('send', 'pageview');
</script>