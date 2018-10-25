<li class="dropdown">  
    <?=
    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' US Market'), '/USD/dashboard', [
        'tabindex' => '-1',
        'class' => 'color_blue',
        'escape' => false
            ]
    );
    ?>
</li >
<li class="dropdown"> 
    <?=
    $this->Html->link($this->Html->tag('i', '', ['class' => 'fa fa-bar-chart']) . __(' Jam Market'), '/JMD/dashboard', [
        'tabindex' => '-1',
        'class' => 'color_red',
        'escape' => false
            ]
    );
    ?>
</li>

