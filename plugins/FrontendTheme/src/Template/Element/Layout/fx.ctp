<li class="dropdown">  
    <?=
    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-exchange']) . __(' Forex'), ['_name'=>'forex'], [
        'tabindex' => '-1',
        'class' => 'color_orange',
        'escape' => false
            ]
    );
    ?>
</li >