<?php
namespace Jvd;
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

// Disable IE compat
$app->response->headers->set('X-UA-Compatible', 'IE=edge');

// Lang tools
$app->container->singleton('lang', function () use ($app) {
	return new Lang($app->request->headers->get('Accept-Language'));
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
					if (isset($arr[$j]))
						$next[] = $arr[$j];
				}

				$columnised[] = $next;
			}

			return $columnised;
		},
	]);
};

$app->get('/', $homeRoute);
$app->get('/:lang', $homeRoute);

$app->run();
