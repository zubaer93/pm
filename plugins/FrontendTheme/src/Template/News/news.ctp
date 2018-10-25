<?= $this->Html->css('new_style/new_style.css'); ?>
<section style="padding: 20px">
    <div class="container-fluid ticker-container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-0"></div>
            <div class="col-lg-8 col-md-8 col-sm-12 mb-80">
                <div class="box-light">
                    <div class="box-inner">
                        <?= $this->element('News/dataTable/news'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-0"></div>
        </div>
</section>