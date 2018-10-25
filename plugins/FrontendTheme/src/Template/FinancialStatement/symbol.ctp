<div class="container">
    <div class="heading-title heading-line-single text-center mt-30">
        <h3><?= __('Quarterly Financial Statements'); ?></h3>
    </div>
    <div class="blog-post-item">
        <h2><?= $financialStatement['title']; ?></h2>
        <ul class="blog-post-info list-inline mb-5">
            <li>
                <a href="#">
                    <i class="fa fa-clock-o"></i> 
                    <span class="font-lato"><?= $financialStatement['created_at']->nice(); ?></span>
                </a>
            </li>
        </ul>
        <?php foreach ($financialStatement['financial_statement_files'] as $val):  ?>
        <p><a target="_blank" href="<?= \Cake\Core\Configure::read('Users.financial.path_sm').$val['file_path'] ?>"><?= $val['file_path']; ?></a></p>
        <?php endforeach; ?>
    </div> 
</div>