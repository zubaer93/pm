<?php use Cake\Core\Configure; ?>

<p>Hello <?= $info->app_user->first_name; ?>.</p>

<p>Because <?= $info->company->name; ?> is on your watchlist we are letting you know:</p>

<?= $this->Html->link($currentRecord->title, Configure::read('News.current_domain') . strtoupper($currentRecord->market) . '/news/' . $currentRecord->slug);?>

<p>Thank You</p>
<p>Penny</p>

<?= $this->Html->image('https://stockgitter.com/frontend_theme/img/_smarty/stockgitter_header_logo.jpg', ['style' => 'width: 70px; height: auto;']); ?>

<br />
<br />

<p><small>The Information and notifications Stockgitter provides is  "as is" and “as available”  without any representations or warranties, express or implied. STOCKGITTER makes no representations or warranties in relation to application service or the information and materials provided on this website. StockGitter is in no way liable for your personal trading use of the information provided and accepts no responsibility for gains or losses incurred.  
Copyright © 2018 <a href="https://stockgitter.com/<?= strtoupper($currentRecord->market); ?>" target="_blank">StockGitter.com All rights reserved.</a></small></p>