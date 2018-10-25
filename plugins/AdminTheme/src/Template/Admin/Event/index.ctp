<section id="middle">
    <header id="page-header">
        <h1><?= __('All Orders'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('Event'), '#'); ?></li>
            <li class="active"><?= __('Add Event'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <div id="panel-1" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong><?= __('Add Event'); ?></strong> <!-- panel title -->
                </span>

                <!-- right options -->
                <ul class="options pull-right list-inline">
                    <li><a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand"></i></a></li>
                </ul>
                <!-- /right options -->

            </div>
            <div class="panel-body events_data" data-company="<?= $this->Url->build(['_name' => 'company_get_company']); ?>">
                <div class="row">
                    <div class="padding-20">
                        <!-- ALERT -->
                        <?= $this->Flash->render() ?>
                        <!-- /ALERT -->
                    </div>
                </div>
                <div class="row">
                    <?= $this->Form->create(null, ['url' => ['_name' => 'create_event'], 'type' => 'post' ,'class' => 'm-0 sky-form']); ?>
                    <div class="col-sm-12 col-lg-12 col-md-12">
                        <label class="input"></label>
                            <?= __('Meeting Title'); ?>
                            <?= $this->Form->control('title', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Meeting Title','value' => '']); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Activity type'); ?>
                        <?= $this->Form->control('activity_type', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Activity type','value' => '']); ?>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-md-6 fancy-form">
                        <label class="input"></label>
                        <?= __('Description'); ?>
                        <?= $this->Form->textarea('description', ['required' => true, 'label' => false, 'type' => 'text', 'rows'=>'1' , 'data-maxlength'=>'200' ,'class' => 'form-control word-count', 'placeholder' => 'Description','value' => '']); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Time'); ?>
                        <input id="time" type="time" name="time" class ='form-control' required>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Location'); ?>
                        <?= $this->Form->control('location', ['required' => true, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Location','value' => '']); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Date'); ?>
                        <?= $this->Form->control('date', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => '' . date('Y-m-d') . '']); ?>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label class="input"></label>
                        <?= __('Meeting link'); ?>
                        <?= $this->Form->control('meeting_link', ['required' => false, 'label' => false, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'http://', 'value' => '']); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label><?= __('Market'); ?></label>
                        <?= $this->Form->select('market', \App\Model\Service\Core::$market, ['empty' => 'Select Market', 'class' => 'form-control select_market', 'required' => true]); ?>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <label><?= __('Company Symbol'); ?></label>
                        <?= $this->Form->control('company_id', ['class' => 'form-control select2 js-company-data-ajax', 'options' => [], 'label' => false]); ?>
                    </div>
                </div>

                <div class="row">
                   <div  class="col-sm-12 col-lg-12 col-md-6">
                       <?= $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-success btn-sm pull-right']);?>
                   </div>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<script>
    $('.select_market').change(function () {
        var getCompany = $('.events_data').attr("data-company");
        var market = $(this).val();

        select2Ajax(market, '.js-company-data-ajax', getCompany, 'company');
    })

    /**
     * params compony and brocker
     * @param market
     * @param className
     */
    function select2Ajax(market, className, url, placeholder) {
        $(className).select2({
            multiple: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        market: market,
                        search: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: convertToJson(data.allCompony)
                    };
                },
                cache: true
            },
            placeholder: {title: 'Search for a ' + placeholder},
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 1,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.name;
            }

            var markup = repo.name;
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }

        function convertToJson(data) {
            var array = [];
            $.each(data, function (index, value) {
                array.push({
                    id: index,
                    name: value
                });
            });

            return array;
        }
    }
</script>