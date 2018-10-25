<?php $this->Html->script(['symbol/script.js'], ['block' => 'script']);?>
<?php $this->Html->script(['analysis/all_analysis.js'], ['block' => 'script']);?>

<div class="popover_content  analysis-table"
    data-delete-url = "<?= $this->Url->build(['_name' => 'delete_analysi']); ?>"
    data-print-url = "<?= $this->Url->build(['_name' => 'print_analysi']); ?>"
    data-edit-partial-url = "<?= $this->Url->build(['_name' => 'analysis_partial']); ?>"
    data-approve-url = "<?= $this->Url->build(['_name' => 'analysis_approve']); ?>"
    data-doc-print-url = "<?= $this->Url->build(['_name' => 'print_analysis-doc']); ?>"
    style="display: none">
    <ul class="list-unstyled pr-15 pl-15 pt-10 mb-15">
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-edit"><i class="fa fa-edit"></i> <?= __('Edit'); ?></a></li>
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-approve"><i class="fa fa-check"></i> <?= __('Approve'); ?></a></li>
        <?php if (in_array($accountType, ['PROFESSIONAL', 'EXPERT'])): ?>
            <li class="popover-content-li"><a href="javascript:;" class="text-black popover-microsoft-word"><i class="fa fa-file-word-o"></i> <?= __('Microsoft Word'); ?></a></li>
        <?php endif; ?>
        <li class="popover-content-li"><a href="javascript:;" class="text-black popover-print"><i class="fa fa-print"></i> <?= __('Print'); ?></a></li>
    </ul>
</div>
<section style="padding: 20px">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-0">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <h3><?= __('Local Fund Analysis'); ?></h3>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-6">
            <div class="mt-p-2 float-right">
                <?= $this->element('Links/quick_links'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
            <div class="float-right">
                <a href="#" class="btn btn-primary btn-sm my-popover-action" data-toggle="popover" data-id="<?= $analysis['id']; ?>"  data-container="body">
                    <i class="fa fa-cog white"></i><?= __('Action'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="box-light mb-30">
                <div class="box-inner">
                    <?= $this->element('Symbol/company_info', ['show_watch_list_button' => true, 'main_div_class' => 'col-lg-12 col-md-12 col-sm-12']); ?>
                </div>
            </div>
            <div class="box-light">
                <div class="box-inner">
                    <?= $analysis['text']; ?>
                </div>
            </div>
            <?php if ($currentLanguage == 'JMD'): ?>
                <div class="box-light mt-30">
                    <div class="box-inner">
                        <?= $this->element('FinancialStatement/list', ['statement' => $statement]); ?> 
                    </div>
                </div>
                <?=
                $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-plus']) . __('More'), 'javascript:;', [
                    'tabindex' => '-1',
                    'escape' => false,
                    'class' => 'btn btn-sm btn-reveal btn-default float-right',
                    'data-toggle' => 'modal',
                    'data-target' => '.bs-example-modal-full'
                        ]
                );
                ?>
                <?= $this->element('FinancialStatement/dataTable', ['symbol' => $companyInfo['symbol']]); ?>
            <?php endif; ?>
            <?php if ($currentLanguage == 'USD'): ?>
                <div class="box-light">
                    <div class="box-inner">
                        <div class="box-messages mb-5">
                            <?php
                            $news = [];
                            $i = 0;
                            foreach ($analysis['analysis_news'] as $val) {
                                if ($i <= 5) {
                                    $i++;
                                    $news[] = $val['news'];
                                }
                            }
                            ?>

                            <h4><?= __('Related News to Local Fund '); ?></h4>
                            <div class="wl-list ">
                                <?= $this->element('News/news', ['news' => $news]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                    <div class="box-light" style="height: 530px;">
                        <?php if ($companyMarket == 'JMD'): ?>
                            <?= $this->element('Chart/jmd_chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>

                        <?php else: ?>
                            <?= $this->element('Chart/chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($companyMarket == 'JMD'): ?>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
                        <div class="box-light">
                            <div class="box-inner">
                                <div class="box-messages mb-5">
                                    <?php
                                    $news = [];
                                    $i = 0;
                                    foreach ($analysis['analysis_news'] as $val) {
                                        if ($i <= 5) {
                                            $i++;
                                            $news[] = $val['news'];
                                        }
                                    }
                                    ?>

                                    <h4><?= __('Related News to Local Fund '); ?></h4>
                                    <div class="wl-list ">
                                        <?= $this->element('News/news', ['news' => $news]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?= $this->element('Watchlist/watchlist', ['showActions' => false, 'showWatchlist' => true]); ?>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-full" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
        <div class="modal-dialog modal-full editModalBody">
        </div>
    </div>
</section>