<section style="padding: 20px">
    <div class="container ticker-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="heading-title text-center mt-10 mb-20">
                    <h3>Search Results for "<?php echo $params['phrase']; ?>"</h3>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php foreach ($companies as $company) { ?>
                    <a class="clearfix" href="<?php echo $company['url']; ?>" data-type="stock"
                       data-query="<?php echo $company['symbol']; ?>">
                        <div class="row">
                            <div class="col-sm-2"><?php echo $company['symbol']; ?></div>
                            <div class="col-sm-4"><?php echo $company['name']; ?></div>
                            <div class="col-sm-6"><?php echo $company['exchange']; ?></div>
                        </div>
                    </a>
                    <hr/>
                <?php } ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php foreach ($trader as $t) { ?>
                    <a class="clearfix"
                       href="<?= $this->Url->build(['_name' => 'forex_currency', 'currency' => $t['from_currency_code'] . '-' . $t['to_currency_code']]) ?>"
                       data-type="stock"
                       data-query="<?php echo $t['from_currency_code'] . '-' . $t['to_currency_code']; ?>">
                        <div class="row">
                            <div class="col-sm-2"><?php echo $t['from_currency_code']; ?></div>
                            <div class="col-sm-4"><?php echo $t['to_currency_code']; ?></div>
                            <div class="col-sm-6"><?php echo $t['exchange']; ?></div>
                        </div>
                    </a>
                    <hr/>
                <?php } ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php foreach ($users as $user) { ?>
                    <a class="clearfix"
                       href="<?= $this->Url->build(['_name' => 'user_name', 'username' => $user['username']]) ?>"
                       data-type="stock" data-query="<?= $user['symbol']; ?>">
                        <div class="col-sm-2"><img style="width:25px;" src='<?php echo $user["icon"]; ?>'/></div>
                        <div class="col-sm-4"><?php echo $user["fullname"]; ?></div>
                        <div class="col-sm-6"><?php echo $user["username"]; ?></div>
                    </a>
                    <hr/>
                <?php } ?>
            </div>
        </div>
        <?php if ($this->Paginator->hasPage(null, 2)): ?>
            <ul class="pagination justify-content-center">
                <?= $this->Paginator->prev('« ' . __('Previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next') . ' »') ?>
            </ul>
        <?php endif; ?>
    </div>
</section>