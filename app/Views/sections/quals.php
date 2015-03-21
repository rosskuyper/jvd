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
		<div class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>">
			<p><?= $link; ?></p>
		</div>
	<?php endforeach; ?>
</section>

<section class="clearfix">
	<h5><?= $lang->get('quals.content.clients.title'); ?></h5>

	<?php foreach ($lang->get('quals.content.clients.para') as $index => $content) : ?>
		<div class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>">
			<p><?= $content; ?></p>
		</div>
	<?php endforeach; ?>
</section>

<section class="clearfix">
	<h5><?= $lang->get('quals.content.samples.title'); ?></h5>

	<?php
	$samples = $lang->get('quals.content.samples');
	foreach ($samples as $index => $link) : ?>
		<?php $class = $index % 2 === 0 ? 'left' : 'right'; ?>

		<?php if ($class === 'left') : ?>
			<div class="clearfix">
		<?php endif; ?>

			<p class="<?= $class; ?>"><?= $link; ?></p>

		<?php if ($class === 'right') : ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if (count($samples) % 2 === 1) : // Close the last tag ?>
		</div>
	<?php endif; ?>

</section>
