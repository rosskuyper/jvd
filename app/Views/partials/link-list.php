<section class="clearfix padd">
	<h5><?= $lang->get($titleName); ?></h5>

	<?php
	$items = $lang->get($itemName);
	foreach ($items as $index => $link) : ?>
		<?php $class = $index % 2 === 0 ? 'left' : 'right'; ?>

		<?php if ($class === 'left') : ?>
			<div class="clearfix">
		<?php endif; ?>

			<p class="<?= $class; ?>"><?= $link; ?></p>

		<?php if ($class === 'right') : ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if (count($items) % 2 === 1) : // Close the last tag ?>
		</div>
	<?php endif; ?>

</section>
