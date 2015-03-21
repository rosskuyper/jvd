<section class="clearfix testimonies">
	<?php
	$columns = $columnise($lang->get('client.testimonies'));
	foreach ($columns as $col) : ?>
		<div class="clearfix">
			<?php foreach ($col as $index => $testimony) : ?>
				<div class="<?= $index === 0 ? 'left' : 'right'; ?>">
					<blockquote><?= $testimony[0]; ?></blockquote>
					<cite>- <?= $testimony[1]; ?></cite>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>

</section>


<section class="clearfix warning"><p><?= $lang->get('services.warning'); ?></p></section>
