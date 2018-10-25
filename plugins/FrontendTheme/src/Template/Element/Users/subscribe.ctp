<div class="row pricetable-container">
	<div class="col-md-3 col-sm-3 price-table">
		<h3>
			<?= __('Individual'); ?> <br />
			<?php if (!empty($authUser) && $accountType == 'FREE'): ?>
				<span class="badge badge-success" style="font-size: 16px;">Current Plan</span>
			<?php endif; ?>
		</h3>
		<p>	
			Free
		</p>
		<ul>
			<li><?= __('Create 1 watchlist'); ?></li>
			<li><?= __('US and Local Market'); ?></li>
			<li><?= __('Crypto Currencies'); ?></li>
			<li><?= __('Local & International News'); ?></li>
			<li><?= __('Auto trade simulation'); ?></li>
			<li><?= __('Penny Personal Assistant Basic'); ?></li>
			<li><?= __('Learn to trade'); ?></li>
		</ul>
		<?php if (empty($authUser)): ?>
			<?= $this->Html->link(__('Login'), ['_name' => 'login'], ['class' => 'btn btn-primary']);?>
		<?php endif;?>
	</div>

	<div class="col-md-3 col-sm-3 price-table popular">
		<h3>
			<?= __('Individual (Non professional)'); ?> <br />
					
			<?php if (!empty($authUser) && $accountType == 'INDIVIDUAL'): ?>
				<span class="badge badge-success" style="font-size: 16px;">Current Plan</span>
			<?php endif; ?>			
		</h3>
		<p>	
			$25.00
			<span>Per month or <br/ >pay by year</span>
		</p>
		<ul>
			<li><?= __('Penny Personal Assistant unlimited'); ?></li>
			<li><?= __('Create 4 Watch-lists'); ?></li>
			<li><?= __('Crypto Currencies'); ?></li>
			<li><?= __('Independent Analysis'); ?></li>
			<li><?= __('Five Markets'); ?></li>
			<li><?= __('Local & International News'); ?></li>
			<li><?= __('Auto and Manual simulation'); ?></li>
			<li><?= __('Create Analysis Using Editor'); ?></li>
			<li><?= __('Join ER and AGM Conference Calls'); ?></li>
			<li><?= __('Bonds'); ?></li>
			<li><?= __('Buy and sell rating'); ?></li>
			<li><?= __('Try for 7 days for $7.00'); ?></li>
		</ul>
		<?php if (!empty($authUser) && $accountType == 'INDIVIDUAL'): ?>
			<?= $this->Html->link(__('Current Plan'), 'javascript:;', ['class' => 'btn btn-default']);?>
		<?php else: ?>
			<?php if (empty($authUser)): ?>
				<?= $this->Html->link(__('Login'), ['_name' => 'login'], ['class' => 'btn btn-default']);?>
			<?php else: ?>
				<?= $this->element('Subscriptions/individual_button'); ?>
			<?php endif;?>
		<?php endif; ?>
	</div>

	<div class="col-md-3 col-sm-3 price-table popular">
		<h3>
			<?= __('Professional'); ?> <br />
					
			<?php if (!empty($authUser) && $accountType == 'PROFESSIONAL'): ?>
				<span class="badge badge-success" style="font-size: 16px;">Current Plan</span>
			<?php endif; ?>
		</h3>
		<p>	
			$30.00
			<span>Per month or <br/ >pay by year</span>
		</p>
		<ul>
			<li><?= __('Penny Personal Assistant unlimited'); ?></li>
			<li><?= __('Unlimited watch list creation'); ?></li>
			<li><?= __('Unlimited Market access'); ?></li>
			<li><?= __('Local & International News'); ?></li>
			<li><?= __('Auto and Manual simulation'); ?></li>
			<li><?= __('AI Advanced Searches and market suggestions'); ?></li>
			<li><?= __('Create Analysis Using Editor'); ?></li>
			<li><?= __('Market Planner + Microsoft word and Outlook'); ?></li>
			<li><?= __('share Analysis with followers'); ?></li>
			<li><?= __('Social Media Sentiment Analysis'); ?></li>
			<li><?= __('Regulatory Analyser'); ?></li>
			<li><?= __('Join ER and AGM Conference Calls'); ?></li>
			<li><?= __('Bonds'); ?></li>
			<li><?= __('Mutual Funds'); ?></li>
			<li><?= __('Sector Analysis'); ?></li>
			<li><?= __('Buy and sell rating'); ?></li>
			<li><?= __('Try for 7 days for $7.00'); ?></li>
		</ul>
		<?php if (!empty($authUser) && $accountType == 'PROFESSIONAL'): ?>
			<?= $this->Html->link(__('Current Plan'), 'javascript:;', ['class' => 'btn btn-default']);?>
		<?php else: ?>
			<?php if (empty($authUser)): ?>
				<?= $this->Html->link(__('Login'), ['_name' => 'login'], ['class' => 'btn btn-default']);?>
			<?php else: ?>
				<?= $this->element('Subscriptions/professional_button'); ?>
			<?php endif;?>
		<?php endif; ?>
	</div>

	<div class="col-md-3 col-sm-3 price-table">
		<h3>
			<?= __('Expert'); ?> <br />
					
			<?php if (!empty($authUser) && $accountType == 'EXPERT'): ?>
				<span class="badge badge-success" style="font-size: 16px;">Current Plan</span>
			<?php endif; ?>
		</h3>
		<p>	
			Contact us Now!
		</p>
		<ul>
			<li><?= __('Watch Now (displays Youtube video here)'); ?></li>
			<li><?= __('Get dedicated Solutions customization for your team'); ?></li>
		</ul>
		<?php if (!empty($authUser) && $accountType == 'EXPERT'): ?>
			<?= $this->Html->link(__('Current Plan'), 'javascript:;', ['class' => 'btn btn-default']);?>
		<?php else: ?>
			<?= $this->Html->link(__('UPGRADE'), ['_name' => 'contact-us'], ['class' => 'btn btn-primary']);?>
		<?php endif; ?>
	</div>
</div>