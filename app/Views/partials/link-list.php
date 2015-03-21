<section class="clearfix padd">
	<h5><?= $lang->get($titleName); ?></h5>

	<?php
	$columns = $columnise($lang->get($itemName));
	foreach ($columns as $col) : ?>
		<div class="clearfix">
			<?php foreach ($col as $index => $item) : ?>
				<p class="<?= $index === 0 ? 'left' : 'right'; ?>"><?= $item; ?></p>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>

</section>
