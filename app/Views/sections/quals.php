<?php

// External links
echo $app->view->fetch('partials/dual-columns.php', [
	'titleName' => 'quals.quals.title',
	'itemName'  => 'quals.quals.content',
]);

?>

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

<?php

// Clients
echo $app->view->fetch('partials/dual-columns.php', [
	'titleName' => 'quals.clients.title',
	'itemName'  => 'quals.clients.content',
]);

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
