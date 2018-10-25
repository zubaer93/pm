<section>
    <div class="container-fluid">
        <div class="row">
            <!-- LEFT -->
            <?= $this->element('Users/sidenav'); ?>
            <!-- RIGHT -->
                    <div class="col-lg-8 col-md-8 col-sm-12 mb-80">
                            <?= $this->Form->create(null, ['url' => ['_name' => 'post_edit'],'type'=>'post','class' => 'm-0 sky-form ']); ?>
                            <div class="box-light">
                                <div class="box-inner">
                                     <?= $this->Form->hidden('id', ['label' => false, 'value' => $data->id]) ?>
                                    <header>
                                        <i class="fa fa-comments-o"></i> <?= __('PRIVATE POST') ?>
                                    </header>
                                    <fieldset class="m-0" style="padding: 10px">
                                        <div class="col-lg-6 col-md-6 col-sm-6 pull-right">
                                            <label class="select">
                                                <?= __('Users'); ?>
                                                <?= $this->Form->select('private_user', $all_users, ['empty' => 'Select Market', 'required' => true, 'default'=>$users, 'multiple' => 'multiple', 'class' => 'form-control select2 users']); ?>
                                            </label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <label class="select">
                                                <?= __('Private Post Name'); ?>
                                                <?= $this->Form->control('post_name', ['required' => true,  'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Private Post Name','value' => $data->name]); ?>
                                            </label>
                                        </div>
                                        <?= $this->Form->button('Add',['type'=>'submit','class'=>'btn btn-success mt-20 mr-10 float-right']); ?>
                                    </fieldset>
                                </div>
                            </div>
                            <?= $this->Form->end(); ?>
                    </div>
        </div>
    </div>
</section>
