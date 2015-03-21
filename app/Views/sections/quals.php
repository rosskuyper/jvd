<section class="clearfix">
	<div class="left">
		<h5><?= $lang->get('quals.content.quals.title'); ?></h5>
		<p><?= $lang->get('quals.content.quals.para'); ?></p>
	</div>
	<div class="right">
		<h5><?= $lang->get('quals.content.creds.title'); ?></h5>
		<p><?= $lang->get('quals.content.creds.para'); ?></p>
	</div>
</section>

<?php

// External links
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.content.links.title',
	'itemName'  => 'quals.content.links.items',
]);

?>

<section class="clearfix">
	<h5><?= $lang->get('quals.content.clients.title'); ?></h5>

	<?php foreach ($lang->get('quals.content.clients.para') as $index => $content) : ?>
		<div class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>">
			<p><?= $content; ?></p>
		</div>
	<?php endforeach; ?>
</section>

<?php

// Samples
echo $app->view->fetch('partials/link-list.php', [
	'titleName' => 'quals.content.samples.title',
	'itemName'  => 'quals.content.samples',
]);

?>
