
<section class="clearfix">
	<h5><?= $lang->get('quals.quals.title'); ?></h5>
	<div class="full-row">
		<?= $lang->paragraph('quals.quals.content'); ?>
	</div>
</section>

<section class="clearfix">
	<div class="left">
		<h5><?= $lang->get('quals.creds.title'); ?></h5>
		<p><?= $lang->get('quals.creds.content'); ?></p>
	</div>
	<div class="right">
		<?php
		// External links
		echo $app->view->fetch('partials/link-list.php', [
			'titleName' => 'quals.links.title',
			'itemName'  => 'quals.links.items',
		]);
		?>
	</div>
</section>

<section class="clearfix">
	<h5><?= $lang->get('quals.clients.title'); ?></h5>
	<div class="full-row">
		<p><?= $lang->get('quals.clients.content'); ?></p>
	</div>
</section>

<?php

// Samples
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.articles.title',
	'itemName'  => 'quals.articles',
]);

// Samples
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.samples.title',
	'itemName'  => 'quals.samples',
]);
