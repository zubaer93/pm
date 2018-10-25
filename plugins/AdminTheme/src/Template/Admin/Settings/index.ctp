<section id="middle">

    <header id="page-header">
        <h1><?= __('Manage Settings'); ?> </h1>
    </header>
    <div id="content" class="padding-20">

        <?= $this->Flash->render(); ?>
        <!-- page title -->
        <div class="panel-body">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Settings'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create($settings, [
                        'url' => [
                            'controller' => 'settings',
                            'action' => 'edit',
                            $settings->id
                        ],
                        'templates' => [
                            'inputContainer' => '{{content}}',
                            'nestingLabel' => '<label class="checkbox">{{input}}<i></i>{{text}}</label>'
                        ]
                    ]); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <?= $this->Form->control('enabled_penny', ['class' => 'form-control']); ?>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']); ?>
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
