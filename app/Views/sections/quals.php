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

<section class="clearfix">
	<h5><?= $lang->get('quals.content.links.title'); ?></h5>

	<?php foreach ($lang->get('quals.content.links.items') as $index => $link) : ?>
		<p class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>"><?= $link; ?></p>
	<?php endforeach; ?>
</section>

<section class="clearfix">
	<h5><?= $lang->get('quals.content.clients.title'); ?></h5>

	<?php foreach ($lang->get('quals.content.clients.para') as $index => $content) : ?>
		<p class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>"><?= $content; ?></p>
	<?php endforeach; ?>
</section>

<section class="clearfix">
	<h5><?= $lang->get('quals.content.samples.title'); ?></h5>

	<?php foreach ($lang->get('quals.content.samples') as $index => $link) : ?>
		<p class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>"><?= $link; ?></p>
	<?php endforeach; ?>
</section>
