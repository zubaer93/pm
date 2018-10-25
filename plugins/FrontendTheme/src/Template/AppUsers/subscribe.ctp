<section class="container-fluid">
	<?php if ($this->request->session()->check('subscribe')): ?>
		<div class="alert alert-mini alert-success mb-30" onclick="this.classList.add('d-none')"><?= $this->request->session()->consume('subscribe') ?></div>
	<?php endif; ?>
	<?= $this->Flash->render(); ?>
	<?= $this->element('Users/subscribe'); ?>
</section>
