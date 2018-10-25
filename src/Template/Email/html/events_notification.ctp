<?php
	use Cake\Core\Configure;
?>

<p>Hello <?= $info->app_user->first_name; ?>,</p>

<br />
<br />

<p>Because <?= $info->company->name; ?> is on your watchlist we are letting you know:</p>

<p><strong>Date: </strong><?= $currentRecord->showDate; ?></p>
<p><strong>Location: </strong><?= $currentRecord->showDate; ?></p>
<p><strong>Title: </strong><a href="https://stockgitter.com/USD/analysis/<?= $info->company->symbol; ?>"><?= $currentRecord->activity_type; ?></a></p>

<?php if ($currentRecord->meeting_link): ?>
	<a href="<?= $currentRecord->meeting_link; ?>"><?= $currentRecord->meeting_link; ?></a>
<?php endif; ?>

<br />
<br />

<p>Thank You</p>
<p>Penny</p>

<br />
<br />

<?= $this->Html->image('https://stockgitter.com/frontend_theme/img/_smarty/stockgitter_header_logo.jpg', ['style' => 'width: 70px; height: auto;']); ?>

<br />
<br />

<p><small>The Information and notifications Stockgitter provides is  "as is" and “as available”  without any representations or warranties, express or implied. STOCKGITTER makes no representations or warranties in relation to application service or the information and materials provided on this website. StockGitter is in no way liable for your personal trading use of the information provided and accepts no responsibility for gains or losses incurred.  
Copyright © 2018 <a href="https://stockgitter.com/USD" target="_blank">StockGitter.com All rights reserved.</a></small></p>

<p style="text-align: center;"><small>To change your alert settings click here: <a href="https://stockgitter.com/USD/profile/alerts">alerts</a></small></p>