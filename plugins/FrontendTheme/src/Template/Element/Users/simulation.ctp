<div class="tab-pane active" id="simulation">
    <?= $this->Flash->render(); ?>
    <?= $this->Form->create($user, ['url' => ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'editSimulation', $user->id]]) ?>
        <div class="form-group">
            <label class="form-control-label"><?= __('Default Investment amount ') ?></label>
            <?= $this->Form->number('investment_amount', ['label' => false, 'class' => 'form-control', 'required' => true,'min'=>1 ,'value' => (isset($simulation['investment_amount'])?$simulation['investment_amount']:100000)]); ?>
        </div>
        <div class="form-group">
            <label class="form-control-label"><?= __('Default Quantity') ?></label>
            <?= $this->Form->number('quantity', ['label' => false, 'class' => 'form-control','required' => true, 'min'=>1 ,'value' => (isset($simulation['quantity'])?$simulation['quantity']:100)]); ?>
        </div>
        <div class="form-group">
            <label class="form-control-label"><?= __('Default Market') ?></label>
            <?= $this->Form->select('market', \App\Model\Service\Core::$market, ['default'=>(isset($simulation['market'])?$simulation['market']:''), 'required' => true, 'class' => 'form-control']); ?>                         
        </div>
        <div class="form-group">
            <label class="form-control-label"><?= __('Default Broker') ?></label>
            <?= $this->Form->select('broker_id', $all_brokers, ['default'=>(isset($simulation['broker_id'])?$simulation['broker_id']:''), 'required' => true, 'class' => 'form-control select2']); ?>                         
        </div>
        <div class="margiv-top10">
            <?= $this->Form->button(__d('CakeDC/Users', '<i class="fa fa-check"></i> '. __('SUBMIT')), ['class' => 'btn btn-primary']); ?>
            <button type="reset" class="btn btn-default"><?= __('Cancel') ?></button>
        </div>
    <?= $this->Form->end() ?>
</div>

