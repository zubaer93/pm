<section id="middle">

    <header id="page-header">
        <h1><?= __('Edit Post'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Post'), ['_name' => 'all_posts']); ?></li>
            <li class="active"><?= __('Edit Post'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit Post'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create('news', ['enctype' => 'multipart/form-data']); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id', ['value' => $post->id]); ?>

                        <div class="row">
                            <div class="col-md-2 col-sm-2">
                                <div class="thumbnail text-center">
                                    <?=
                                    $this->Html->image(
                                            $post->app_user->avatarPath, ['width' => '460', 'height' => '460', 'class' => 'img-fluid']
                                    );
                                    ?>
                                    <h4>
                                        <?=
                                        $this->Html->tag(
                                                'span', $post->app_user->fullName, ['class' => 'full_name']
                                        )
                                        ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <label><?= __('Message'); ?></label>
                                        <?= $this->Form->textarea('message', ['type' => 'text', 'maxlength' => '140', 'data-height' => '400', 'data-lang' => 'en-US', 'class' => 'form-control', 'placeholder' => $post->message, 'value' => $post->message]); ?>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <label><?= __('Company *'); ?></label>
                                        <?= $this->Form->select('company_id', $all_companies, ['default' => $post->company_id, 'empty' => __('Select Company'), 'class' => 'form-control select2']); ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <label><?= __('Market *'); ?></label>
                                        <?= $this->Form->select('market', $all_countries, ['default' => $post->country->id, 'empty' => 'Select Market', 'class' => 'form-control', 'required' => true]); ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <?= $this->Form->button('<i class="fa fa-check"></i>' . __('SAVE'), ['class' => 'btn btn-primary btn-lg btn-block']); ?>

                            </div>
                        </div>




                    </fieldset>
                    <?= $this->Form->end(); ?>
                </div>

                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

</section>