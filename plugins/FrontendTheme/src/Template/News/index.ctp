<?= $this->Html->meta(
    'og:image',
    App\Model\Service\Core::getImagePath($data->urlToImage)
);
?>
<?= $this->Html->meta(
    'og:description',
     $data->title
);
?>
<?= $this->Html->meta(
    'og:title',
     $data->title
);
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <h1 class="blog-post-title"><?= $data->title ?></h1>
                <ul class="blog-post-info list-inline">
                    <li>
                        <a href="#">
                            <i class="fa fa-clock-o"></i> 
                            <span class="font-lato"><?= date("F d Y", strtotime($data->publishedAt)); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-user"></i> 
                            <span class="font-lato"><?= $data->author ?></span>
                        </a>
                    </li>
                </ul>
                <div class="blog-single-small-media inverse">
                    <div class="owl-carousel buttons-autohide controlls-over m-0 owl-theme owl-carousel-init" data-plugin-options='{"items": 1, "autoPlay": false, "autoHeight": false, "navigation": false, "pagination": false, "transitionStyle":"fadeUp", "progressBar":"false"}' >
                        <?= $this->Html->link($this->Html->image(App\Model\Service\Core::getImagePath($data->urlToImage), ['class' => 'img-fluid']), $data->urlToImage, ['escape' => false], ['class' => 'lightbox']); ?>
                    </div>
                    <div class="caption-light">
                        <?= $data->description ?>
                    </div>
                </div>
                <?= $data->body; ?>
                <?=
                    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-chevron-right']) . __('More News...'), '/'.$currentLanguage.'/news', [
                        'tabindex' => '-1',
                        'escape' => false,
                        'class' => 'btn btn-sm btn-reveal btn-default float-right'
                            ]
                    );
                    ?>
            </div>
        </div>
    </div>
</section>