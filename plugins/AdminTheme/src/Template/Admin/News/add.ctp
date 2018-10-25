<section id="middle">

    <header id="page-header">
        <h1><?= __('Add News'); ?></h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('News'), ['_name' => 'news_list']); ?></li>
            <li class="active"><?= __('Add News'); ?></li>
        </ol>
    </header>

    <div class="panel panel-default padding-20">
        <div class="panel-heading panel-heading-transparent">
            <strong><?= __('Run Scheduler Manually'); ?></strong>
        </div>
        <div class="panel-body">
            <div id="">
                <div class="row">
                    <div class="col-md-12">
                        <?= $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-plus'))
                                . $this->Html->tag('span', __('Run')), [
                            '_name' => 'run_schedule_manual'
                                ], [
                            'escape' => false,
                            'class' => 'edit btn btn-3d btn-sm btn-reveal btn-success'
                                ]
                        );?>
                    </div>
                </div>

                <!-- ALERT -->
                <?= $this->Flash->render(); ?>
                <!-- /ALERT -->
            </div>
        </div>
    </div>

    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body news_data" data-company="<?= $this->Url->build(['_name' => 'company_get_company']); ?>">
            <div class="card card-default">
                <div class="card-block">
                    <?= $this->Form->create($news, ['type' => 'file']); ?>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Source Name *'); ?></label>
                                <?= $this->Form->control('source_name', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Source Name']); ?>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Author *'); ?></label>
                                <?= $this->Form->control('author', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Author']); ?>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Market *'); ?></label>
                                <?= $this->Form->select('market', \App\Model\Service\Core::$market, ['empty' => 'Select Market', 'class' => 'form-control select_market', 'required' => true]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Title *'); ?></label>
                                <?= $this->Form->control('title', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Title']); ?>
                            </div>
                            <div class="col-md-6 col-sm-5">
                                <?=  $this->Form->control('companies._ids', ['label' => __('Company Symbol'), 'options' => [], 'class' => 'form-control company js-company-data-ajax select2']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm12">
                                <label><?= __('Description *'); ?></label>
                                <?= $this->Form->textarea('description', ['required' => true, 'type' => 'text', 'data-height' => '250', 'data-lang' => 'en-US', 'class' => 'form-control', 'placeholder' => 'Description']); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm12">
                                <label><?= __('Website *'); ?></label>
                                <?= $this->Form->control('url', ['required' => true, 'type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => 'http://']); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <?= __('File Attachment '); ?>
                                </label>

                                <!-- custom file upload -->
                                <div class="fancy-file-upload fancy-file-primary">
                                    <i class="fa fa-upload"></i>
                                    <input type="file" class="form-control valid" name="image" onchange="jQuery(this).next('input').val(this.value);">
                                    <input type="text" class="form-control" placeholder="no image selected" readonly="">
                                    <span class="button"> <?= __('Choose Image'); ?></span>
                                </div>
                                <small class="text-muted block"><?= __('Max file size: 10Mb (jpg/png) '); ?></small>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label><?= __('Body'); ?> </label>
                                <?= $this->Form->textarea('body', ['type' => 'text', 'data-height' => '200', 'data-lang' => 'en-US', 'class' => 'form-control summernote', 'placeholder' => 'Body']); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Published At'); ?> </label>
                                <?= $this->Form->control('publishedAt', ['required' => true, 'type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => '' . date('Y-m-d') . '']); ?>

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
            </div>
        </div>
    </div>
</section>
<script>
    $('.select_market').change(function () {
        var getCompany = $('.news_data').attr("data-company");
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