<!-- recent news -->
<?php foreach ($news_jamaican as $val): ?>
    <div class="row tab-post"><!-- post -->
         <div class="col-lg-3 col-md-12 col-sm-12">
            <?= $this->Html->link(
                    $this->Html->image(App\Model\Service\Core::getImagePath($val['urlToImage']), ['width' => 60]),
                    [
                        '_name' => 'news',
                        'slug' => $val['slug'],
                        'lang' => 'JMD',
                    ],
                    [
                        'escape' => false
                    ]
                ); 
            ?>
        </div>
         <div class="col-lg-9 col-md-12 col-sm-12">
            <?= $this->Html->link($val['title'], 
                    [
                        '_name' => 'news',
                        'slug' => $val['slug'],
                        'lang' => 'JMD',
                    ], 
                    [
                        'class' => 'tab-post-link'
                    ]
                ); 
            ?>
            <small><?= date("F d Y", strtotime($val['publishedAt'])); ?></small>
        </div>
    </div><!-- /post -->
<?php endforeach; ?>
<!-- /recent news -->
