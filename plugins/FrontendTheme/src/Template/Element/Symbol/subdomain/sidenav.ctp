<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">    
        <div class="box-light">
            <div class="box-inner" style="height: 530px;">
                <?php if ($currentLanguage == 'JMD'): ?>
                    <?= $this->element('Chart/jmd_chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>
                <?php else: ?>
                    <?= $this->element('Chart/chart', ['symbol' => $companyInfo['symbol'], 'name' => $companyInfo['name']]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>