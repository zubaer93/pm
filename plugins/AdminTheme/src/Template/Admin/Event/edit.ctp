<section id="middle">
    <header id="page-header">
        <h1><?= __('All Orders'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Event'), '#'); ?></li>
            <li class="active"><?= __('Edit Event'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('Edit Event'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                <!-- /right options -->
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="padding-20">
                        <!-- ALERT -->
                        <?= $this->Flash->render() ?>
                        <!-- /ALERT -->
                    </div>
                </div>
                <div class="row">
                    <?= $this->Form->create(null, ['url' => ['_name' => 'edit_event', 'id' => $event->id], 'type' => 'post', 'class' => 'm-0 sky-form']); ?>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Meeting Title'); ?>
                        <?= $this->Form->control('title', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Meeting Title', 'value' => $event->title]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Activity type'); ?>
                        <?= $this->Form->control('activity_type', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Activity type', 'value' => $event->activity_type]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6 fancy-form">
                        <label class="input"></label>
                        <?= __('Description'); ?>
                        <?= $this->Form->textarea('description', ['required' => true, 'label' => false, 'type' => 'text', 'rows' => '1', 'data-maxlength' => '200', 'class' => 'form-control word-count', 'placeholder' => 'Description', 'value' => $event->description]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Time'); ?>
                        <input id="time" type="time" name="time" class="form-control" value="<?= date("H:i:s", strtotime($event->time)); ?>" required>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Location'); ?>
                        <?= $this->Form->control('location', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Location', 'value' => $event->location]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Date'); ?>
                        <?= $this->Form->control('date', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'default' => (!is_null($event->date))?$event->date->nice():'', 'label' => false]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Meeting link'); ?>
                        <?= $this->Form->control('meeting_link', ['required' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'http://', 'value' => $event->meeting_link]); ?>
                    </div>
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label><?= __('Company Symbol'); ?></label>
                        <?= $this->Form->select('company_id', $company, ['id' => 'dates-field2', 'class' => 'form-control select2', 'default' => $event->company_id]); ?>
                    </div>
                    <div  class="col-sm-12 col-lg-12 col-md-6">
                        <?= $this->Form->button('Edit', ['type' => 'submit', 'class' => 'btn btn-success btn-sm pull-right']); ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>

        </div>
    </div>
</section>