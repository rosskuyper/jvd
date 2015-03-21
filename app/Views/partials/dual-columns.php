<section class="clearfix">
	<h5><?= $lang->get($titleName); ?></h5>

	<?php foreach ($lang->get($itemName) as $index => $content) : ?>
		<div class="<?= $index % 2 === 0 ? 'left' : 'right'; ?>">
			<p><?= $content; ?></p>
		</div>
	<?php endforeach; ?>
</section>
