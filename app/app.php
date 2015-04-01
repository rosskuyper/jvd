<?php
namespace Jvd;
use Dotenv;
use Slim\Slim;
use Jvd\Lang;

/**
 * Author: Ross Kuyper <rosskuyper@gmail.com>
 */
// Slim instance
$app = new Slim([
	'debug'          => file_exists(__DIR__ . "/debug"),
	'templates.path' => __DIR__ . '/Views'
]);

// Disable IE compat mode
$app->response->headers->set('X-UA-Compatible', 'IE=edge');

// Lang tools
$app->container->singleton('lang', function () use ($app) {
	return new Lang($app->request->headers->get('Accept-Language'));
});

// Database connection
$app->container->singleton('db', function () use ($app) {
	Dotenv::load(__DIR__);

	return new DB(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
});

/**
 * One page app
 */
$homeRoute = function($lang = null) use ($app) {
	if (! is_null($lang) )
		$app->lang->setLang($lang);

	$app->render('home.php', [
		'lang'      => $app->lang->getLangData(),
		'sections'  => ['about','quals','services','client','faq','contact'],
		'app'       => $app,
		// A util - could benefit from being put into a lib.
		'columnise' => function($arr){
			// ensure we get just the values
			$arr        = array_values($arr);
			$count      = count($arr);
			$columnised = [];

			// Loop through two at a time.
			for ($i = 0; $i < $count; $i = $i+2) {
				$next = [];

				for ($j = $i; $j <= $i + 1; $j++) {
					if (isset($arr[$j])) {
						$next[] = $arr[$j];
					}
				}

				$columnised[] = $next;
			}

			return $columnised;
		},
	]);
};

// Uploads
$app->map('/upload', function() use ($app) {
	$uploader = new \Jvd\Uploader(
		$app->request->params('resumableChunkNumber'),
		$app->request->params('resumableChunkSize'),
		$app->request->params('resumableTotalSize'),
		$app->request->params('resumableIdentifier'),
		$app->request->params('resumableFilename'),
		$app->request->params('resumableRelativePath'),
		$app->request->params('resumableCurrentChunkSize')
	);

	if ($app->request->getMethod() === 'GET') {
		if ( $uploader->isValidChunk() ) {
			$app->halt(200, "Ok");
		} else {
			$app->halt(404, "Not Found");
		}
	} else {
		$uploader->handleUpload();

		$this->app->halt(200, "Ok");
	}
})->via(['GET','POST']);

$app->get('/', $homeRoute);
$app->get('/en', function() use($homeRoute) {return $homeRoute('en');});
$app->get('/fr', function() use($homeRoute) {return $homeRoute('fr');});

$app->run();
