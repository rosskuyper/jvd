<section class="clearfix">
	<div class="left">
		<h5><?= $lang->get('quals.quals.title'); ?></h5>
		<p><?= $lang->get('quals.quals.content'); ?></p>
	</div>
	<div class="right">
		<h5><?= $lang->get('quals.creds.title'); ?></h5>
		<p><?= $lang->get('quals.creds.content'); ?></p>
	</div>
</section>

<?php

// External links
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.links.title',
	'itemName'  => 'quals.links.items',
]);

// Clients
echo $app->view->fetch('partials/dual-columns.php', [
	'titleName' => 'quals.clients.title',
	'itemName'  => 'quals.clients.content',
]);

// Samples
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.samples.title',
	'itemName'  => 'quals.samples',
]);
