<li class="dropdown markets-dropdown">
  <?= $this->Html->link(
    $this->Html->tag('i', '', ['class' => 'fa fa-dollar']) . 
    __(' Bonds'), ['_name' => 'bonds'], 
    ['tabindex' => '-1', 'class' => 'color_purple', 'escape' => false]
  ); ?>
</li>