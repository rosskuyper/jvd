<!DOCTYPE html>
<html lang="en-US" class="no-js">
<head>
	<meta charset="UTF-8">
	<title>JVD Translations</title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/style.css"/>
</head>
<body>
	<div class="hero">
		<div>
			<h1><span>Jennifer van Dorsten</span></h1>
			<h2><?= $lang->get('subtitle'); ?></h2>
			<p><button type="button">Hire me today</button></p>

			<?php if ($lang->get('lang') === 'en') : ?>
				<a href="/fr" class="lang-select lang-fr">Français</a>
			<?php else : ?>
				<a href="/en" class="lang-select lang-en">English</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="main clearfix" role="main">
		<?php foreach ($sections as $index => $section) : ?>
			<div class="card card-<?= $section; ?>" data-dialog="<?= $section; ?>">
				<h3><?= $lang->get( $section . '.title'); ?></h3>
				<p><?= $lang->get( $section . '.excerpt'); ?></p>
				<p class="readmore"><button class="<?= $index < 2 ? 'purple' : ($index < 4 ? 'red' : 'blue'); ?>" type="button"><?= $lang->get('readmore'); ?></button></p>
			</div>
		<?php endforeach; ?>
	</div>

	<footer>
		<p>Copyright <?php echo date('Y'); ?> - JVD Translations</p>
		<p class="creds">Photo by <a href="http://www.ericnathan.com/" target="_blank">Eric Nathan</a> and Icons by <a target="_blank" href="http://icons8.com/">Icons8</a></p>
		<p><a class="piandcake" href="http://piandcake.com" target="_blank" title="piandcake"></a></p>
	</footer>

	<?php foreach ($sections as $index => $section) : ?>
		<div id="dialog-<?= $section; ?>" class="dialog <?= $section; ?> <?= $index < 2 ? 'purple' : ($index < 4 ? 'red' : 'blue'); ?>">
			<div class="dialog__overlay"></div>
			<div class="dialog__content">
				<h3><?= $lang->get($section . '.title'); ?></h3>
				<h4><?= $lang->get($section . '.subtitle'); ?></h4>

				<div class="content clearfix">
					<?php echo $app->view->fetch('sections/' . $section . '.php', ['section' => $section]); ?>
				</div>
				<div class="tools">
					<button type="button" class="close" data-dialog-close></button>
					<button type="button" class="next"></button>
					<button type="button" class="prev"></button>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<script src="/js/modernizr.custom.js"></script>

	<script src="/js/classie.js"></script>
	<script src="/js/dialogFx.js"></script>

	<script src="/js/main.min.js"></script>
	<?php if (\Slim\Slim::getInstance()->config('debug') === true) : ?>
		<script src="http://localhost:35729/livereload.js"></script>
	<?php endif; ?>
</body>
</html>
