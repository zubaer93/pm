<div class="container">
    <div class="heading-title heading-line-single text-center mt-30">
        <h3><?= __('Financial Statements'); ?></h3>
    </div>
    <?php foreach ($financialStatement as $val): ?> 
        <div class="blog-post-item mb-30 pb-30">
            <h2><a href="<?= $this->Url->build(['_name'=>'financial_statements_symbol','symbol'=>$val['company']['symbol'].'_'.$val['id']])?>"><?= $val['title']; ?></a></h2>
            <ul class="blog-post-info list-inline mb-5">
                <li>
                    <a href="#">
                        <i class="fa fa-clock-o"></i> 
                        <span class="font-lato"><?= $val['created_at']->nice(); ?></span>
                    </a>
                </li>
            </ul>
            <a href="<?= $this->Url->build(['_name'=>'financial_statements_symbol','symbol'=>$val['company']['symbol'].'_'.$val['id']])?>" class="btn btn-reveal btn-primary b-0 btn-shadow-1 mb-5">
                <i class="fa fa-plus"></i>
                <span>Read More</span>
            </a>
        </div>
    <?php endforeach; ?>  
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->prev('« ' . __('Previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Next') . ' »') ?>
    </ul>
</div>