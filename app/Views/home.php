<!DOCTYPE html>
<html lang="en-US" class="no-js">
<head>
	<meta charset="UTF-8">
	<title>JvD Translations</title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/style.css"/>

	<!--[if lt IE 9]><link rel="shortcut icon" href="/favicon.ico" /><![endif]-->
	<!--[if gte IE 9]><!--><link rel="icon" type="image/png" href="/favicon.png" /><!--<![endif]-->

	<meta property="og:type" content="website" />
	<meta property="og:title" content="JvD Translations" />
	<meta property="og:description" content="<?= $lang->get('subtitle'); ?>" />
	<meta property="og:url" content="<?= $app->request->getUrl(); ?>/" />
	<meta property="og:site_name" content="JvD Translations" />
	<meta property="og:image" content="<?= $app->request->getUrl(); ?>/img/JVDTranslations1200x630.jpg" />
	<meta property="og:image:width" content="1200" />
	<meta property="og:image:height" content="630" />
	<meta property="og:locale" content="en_US" />
</head>
<body>
	<div class="hero">
		<div>
			<h1><span>Jennifer van Dorsten</span></h1>
			<h2><?= $lang->get('subtitle'); ?></h2>
			<p><button type="button" id="hire-me" class="rounded-btn red"><?= $lang->get('hireme'); ?></button></p>

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
				<p class="blurb"><?= $lang->get( $section . '.excerpt'); ?></p>
				<p class="readmore"><button class="<?= $index < 2 ? 'purple' : ($index < 4 ? 'red' : 'blue'); ?>" type="button"><?= $lang->get('readmore'); ?></button></p>
			</div>
		<?php endforeach; ?>
	</div>

	<footer>
		<p><?= $lang->get('copyright'); ?> <?= date('Y'); ?> - JvD Translations</p>
		<p class="creds"><?= $lang->get('creds'); ?></p>
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
				<div class="tools tools-bot">
					<button type="button" class="close"></button>
					<button type="button" class="next"></button>
					<button type="button" class="prev"></button>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<nav id="mobile-site-navigation" class="mobilenav" role="navigation">
		<ul>
			<?php foreach ($sections as $index => $section) : ?>
				<li><button type="button" data-dialog="<?= $section; ?>"><?= $lang->get($section . '.title'); ?></button></li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<button class="hamburger-icon" type="button">
		<span class="hamburger">
			<span class="menui top-menu"></span>
			<span class="menui mid-menu"></span>
			<span class="menui bottom-menu"></span>
		</span>
	</button>

	<div class="contact-success" id="contact-success">
		<h5><?= $lang->get('thanks.title'); ?></h5>
		<p><?= $lang->get('thanks.content'); ?></p>
		<button type="button"></button>
	</div>

	<!--[if lt IE 9]><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script><![endif]-->
	<!--[if gte IE 9]><!--><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script><!--<![endif]-->
	<script src="/js/lib.js"></script>
	<script src="/js/main.min.js"></script>
	<?php if ($app->getMode() === "development") : ?>
		<script src="http://localhost:35729/livereload.js"></script>
	<?php endif; ?>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', '<?= getenv('GOOGLE_ANALYTICS_ID'); ?>', 'auto');
		ga('send', 'pageview');
	</script>
</body>
</html>
