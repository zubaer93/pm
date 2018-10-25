<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']); ?>
<?= $this->Flash->render(); ?>

<?= $this->Form->create($user, [
    'templates' => [
        'inputContainer' => '{{content}}'
    ],
    'widgets' => [
        'datetime' => 'input'
    ]
]);?>
    <div class="row ticker-container"
        data-user-id="<?= $user->id; ?>"
        data-watchlist-url="<?= $this->Url->build(['_name' => 'getWatchlist']); ?>"
        data-watchlist-verify-url="<?= $this->Url->build(['_name' => 'verifyWatchlist']); ?>"
        data-watchlist-toggle-url="<?= $this->Url->build(['_name' => 'toggleWatchList']); ?>"
        data-stocks-info-url="<?= $this->Url->build(['_name' => 'getStocksInfo']); ?>"
    >
        <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <?= __('Email Alert'); ?>
                </div>
                <div class="card-block">
                    <?php foreach ($globalNotifications as $globalNotification): ?>
                        <?php $checked = false; ?>
                        <?php foreach ($user->email_alerts as $alert): ?>
                            <?php if ($alert->global_alert_id == $globalNotification->id): ?>
                                <?php $checked = true;?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="checkbox">
                                    <?= $this->Form->control('email_alerts.global_alert_id.' . $globalNotification->id, [
                                        'type' => 'checkbox',
                                        'class' => 'custom-control-input',
                                        'label' => false,
                                        'checked' => $checked
                                    ]); ?>
                                    <i></i> <?= $globalNotification->name; ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr />

                    <?php
                        $timeOfDay = false;
                        $whenHappens = false;
                    ?>
                    <?php foreach ($user->time_alerts as $timeAlerts): ?>
                        <?php if ($timeAlerts->kind == 'email'): ?>
                            <?php
                                $timeOfDay = $timeAlerts->time_of_day;
                                $whenHappens = $timeAlerts->when_happens;
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?> 

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="time-input">Set one time of day to receive all emails notifications</label>
                                <?= $this->Form->control('email_alerts.time_alerts.time_of_day', [
                                    'empty' => true,
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'default' => $timeOfDay
                                ]);?>
                            </div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="checkbox">
                                <?= $this->Form->control('email_alerts.time_alerts.when_happens', [
                                    'type' => 'checkbox',
                                    'class' => 'custom-control-input',
                                    'label' => false,
                                    'checked' => $whenHappens
                                ]); ?>
                                <i></i> Receive notification as it happens
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <?= __('Sms Alert'); ?>
                </div>
                <div class="card-block">
                    <?php foreach ($globalNotifications as $globalNotification): ?>
                        <?php $checked = false; ?>
                        <?php foreach ($user->sms_alerts as $alert): ?>
                            <?php if ($alert->global_alert_id == $globalNotification->id): ?>
                                <?php $checked = true;?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="checkbox">
                                    <?= $this->Form->control('sms_alerts.global_alert_id.' . $globalNotification->id, ['type' => 'checkbox', 'class' => 'custom-control-input', 'label' => false, 'checked' => $checked]); ?>
                                    <i></i><?= $globalNotification->name; ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr />

                    <?php
                        $timeOfDay = false;
                        $whenHappens = false;
                    ?>
                    <?php foreach ($user->time_alerts as $timeAlerts): ?>
                        <?php if ($timeAlerts->kind == 'sms'): ?>
                            <?php
                                $timeOfDay = $timeAlerts->time_of_day;
                                $whenHappens = $timeAlerts->when_happens;
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?> 

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="time-input">Set one time of day to receive all emails notifications</label>
                                <?= $this->Form->control('sms_alerts.time_alerts.time_of_day', [
                                    'empty' => true,
                                    'type' => 'time',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'default' => $timeOfDay
                                ]);?>
                            </div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="checkbox">
                                <?= $this->Form->control('sms_alerts.time_alerts.when_happens', [
                                    'type' => 'checkbox',
                                    'class' => 'custom-control-input',
                                    'label' => false,
                                    'checked' => $whenHappens
                                ]); ?>
                                <i></i> Receive notification as it happens
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?= $this->Form->button('Save', ['class' => 'btn btn-primary float-right', 'type' => 'submit'])?>
        </div>
    </div>
<?= $this->Form->end(); ?>