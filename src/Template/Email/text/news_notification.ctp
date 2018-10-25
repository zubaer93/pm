<?php use Cake\Core\Configure; ?>

<p>Hello <?= $info->app_user->first_name; ?>.</p>

<p>Because <?= $info->company->name; ?> is on your watchlist we are letting you know:</p>

<?= $this->Html->link($currentRecord->title, Configure::read('News.current_domain') . $currentRecord->market . '/news/' . $currentRecord->slug);?>
