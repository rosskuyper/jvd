<div class="left">
	<?php foreach($lang->get('faq.column.left') as $faq) : ?>
		<h5><?= $faq[0]; ?></h5>
		<p><?= $faq[1]; ?></p>
	<?php endforeach; ?>
</div>
<div class="right">
	<?php foreach($lang->get('faq.column.right') as $faq) : ?>
		<h5><?= $faq[0]; ?></h5>
		<p><?= $faq[1]; ?></p>
	<?php endforeach; ?>
</div>
