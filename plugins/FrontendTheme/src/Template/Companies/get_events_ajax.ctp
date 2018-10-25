<?php if (!$events->isEmpty()): ?>
    <?php foreach ($events as $event): ?>
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <p>
                    <small><?= $event->company->symbol; ?> - <?= $event->company->name; ?></small>
                </p>
            </div>

            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <p class="pull-right">
                    <small><?= $event->date->nice(); ?></small>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= $this->Html->link($event->activity_type, ['_name' => 'analysis', $event->company->symbol, 'events']); ?>
            </div>
        </div>

        <hr />
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center"><?= __('There is no data available'); ?></p>
<?php endif; ?>
