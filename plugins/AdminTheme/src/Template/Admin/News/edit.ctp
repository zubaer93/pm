<section id="middle">
    <header id="page-header">
        <h1><?= __('Edit News'); ?> </h1>
        <ol class="breadcrumb">
            <li><?= $this->Html->link(__('News'), ['_name' => 'news_list']); ?></li>
            <li class="active"><?= __('Edit News'); ?></li>
        </ol>
    </header>
    <div id="content" class="padding-20">
        <!-- page title -->
        <div class="panel-body news_data" data-company="<?= $this->Url->build(['_name' => 'company_get_company']); ?>">
            <div class="card card-default">
                <div class="card-heading card-heading-transparent">
                    <h2 class="card-title"><?= __('Edit News'); ?></h2>
                </div>

                <div class="card-block">
                    <?= $this->Form->create($news, [
                        'type' => 'file',
                        'templates' => [
                            'inputContainer' => '{{content}}'
                        ]
                    ]); ?>
                    <fieldset>
                        <?= $this->Form->hidden('id', ['value' => $news->id]); ?>

                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Source Name *'); ?></label>
                                <?= $this->Form->control('source_name', ['type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $news->source_name, 'value' => $news->source_name]); ?>

                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Author *'); ?></label>
                                <?= $this->Form->control('author', ['type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $news->author, 'value' => $news->author]); ?>

                            </div>
                            <div class="col-md-4 col-sm-4">
                                <label><?= __('Market *'); ?></label>
                                <i class=""></i>
                                <?= $this->Form->select('market', \App\Model\Service\Core::$market, ['default' => $news->market, 'empty' => 'Select Market', 'class' => 'form-control select_market', 'required' => true]); ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label><?= __('Title *'); ?></label>
                                <?= $this->Form->control('title', ['type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $news->title, 'value' => $news->title]); ?>

                            </div>
                            <div class="col-md-6 col-sm-5">        
                                <?=  $this->Form->control('companies._ids', ['label' => __('Company Symbol'), 'options' => $companies, 'class' => 'form-control company js-company-data-ajax select2']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm12">
                                <label><?= __('Description *'); ?></label>
                                <?= $this->Form->textarea('description', ['type' => 'text', 'data-height' => '250', 'data-lang' => 'en-US', 'class' => 'form-control', 'placeholder' => $news->description, 'value' => $news->description]); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm12">
                                <label><?= __('Website *'); ?></label>
                                <?= $this->Form->control('url', ['type' => 'text', 'class' => 'form-control', 'label' => false, 'placeholder' => $news->url, 'value' => $news->url]); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="<?= \App\Model\Service\Core::getImagePath($news['urlToImage']); ?>"  width="80%">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label>
                                    <?= __('File Attachment'); ?> 
                                </label>

                                <!-- custom file upload -->
                                <div class="fancy-file-upload fancy-file-primary">
                                    <i class="fa fa-upload"></i>
                                    <?= $this->Form->control('image', ['type' => 'file', 'class' => 'form-control', 'label' => false, 'onclick' => "jQuery(this).next('input').val(this.value));"]); ?>
                                    <input type="text" class="form-control" placeholder="no image selected" readonly="">
                                    <span class="button"><?= __('Choose Image'); ?></span>
                                </div>
                                <small class="text-muted block"><?= __('Max file size: 10Mb (jpg/png)'); ?></small>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label><?= __('Body'); ?> </label>
                                <?= $this->Form->textarea('body', ['type' => 'text', 'data-height' => '200', 'data-lang' => 'en-US', 'class' => 'form-control summernote', 'placeholder' => $news->body, 'value' => $news->body]); ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label><?= __('Published At'); ?></label>
                                <?= $this->Form->control('publishedAt', ['type' => 'text', 'class' => 'form-control datepicker', 'data-lang' => 'en', 'data-rtl' => 'false', 'data-format' => 'yyyy-mm-dd', 'label' => false, 'placeholder' => $news->publishedAt, 'value' => date('Y-m-d', strtotime($news->publishedAt))]); ?>

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
<script>
    $(document).ready(function () {
        var getCompany = $('.news_data').attr("data-company");

        $('.select_market').change(function () {
            var market = $(this).val();

            select2Ajax(market, '.js-company-data-ajax', getCompany, 'company');
        })
        var time = setTimeout(function () {
                select2Ajax('<?= $news->market; ?>', '.js-company-data-ajax', getCompany, 'company');
                clearTimeout(time);
        }, 5000);

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
                            search: params.term, // search term
                            page: params.page
                        };
                    },

                    processResults: function (data) {
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
    });
</script>