<?php

// Main services
echo $app->view->fetch('partials/dual-columns.php', [
	'titleName' => 'services.translation.title',
	'itemName'  => 'services.translation.content',
]);

?>

<section class="clearfix">
	<div class="left">
		<h5><?= $lang->get('services.pm.title'); ?></h5>
		<?= $lang->paragraph('services.pm.content'); ?>
	</div>
</section>

<section class="clearfix warning"><p><?= $lang->get('services.warning'); ?></p></section>
