<?php
namespace Jvd;
use RossKuyper\DB\DB;
use Dotenv;
use Slim\Slim;
use Jvd\Lang;

// Env
Dotenv::load(__DIR__);

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
	return new DB(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));
});

// Flow uploader - Not a singleton
$app->uploadFile = function() {
	$config = new \Flow\Config();
	$config->setTempDir( getenv('CHUNK_DIR') );
	$file = new \Flow\File($config);

	return $file;
};

// Random Str Generator (fixed length)
// Credit to Laravel
$app->randomStr = function(){
	$length = 32;

	$bytes = openssl_random_pseudo_bytes($length * 2);
	if ($bytes === false)
	{
		// Fallback. It's just a filename - not too critical.
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
	return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
};

$app->mailgun = function() {
	return new \Mailgun\Mailgun( getenv('MAILGUN_API_KEY') );
};

/**
 * ---------
 * Routes
 * ---------
 * Mostly a one page "app"
 */
$homeRoute = function($lang = null) use ($app) {
	if (! is_null($lang) )
		$app->lang->setLang($lang);

	$app->render('home.php', [
		'lang'        => $app->lang->getLangData(),
		'sections'    => ['about','quals','services','client','faq','contact'],
		'app'         => $app,
		'uploadToken' => $app->randomStr,
		// A util - could benefit from being put into a lib.
		'columnise'   => function($arr){
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

$app->get('/', $homeRoute);
$app->get('/en', function() use($homeRoute) {return $homeRoute('en');});
$app->get('/fr', function() use($homeRoute) {return $homeRoute('fr');});

/**
 * Uploads
 */
$app->map('/upload', function() use ($app) {
	$file = $app->uploadFile;

	if ($app->request->getMethod() === 'GET') {
		if ($file->checkChunk()) {
			// Chunk found
			$app->response->setStatus(200);
		} else {
			// Chunk not found - needs to be uploaded (this stops the process)
			$app->halt(204);
		}
	} else {
		if ($file->validateChunk()) {
			$file->saveChunk();
		} else {
			// error, invalid chunk upload request, retry
			$app->halt(400);
		}
	}

	// All chunks have been uploaded
	if ( $file->validateFile() ) {
		// DB Interface
		$db = $app->db;

		// Let's make a new name for the file.
		do {
			// Generate a new random str
			$filename = $app->randomStr;

			// Create a query to check for it
			$dbCheck = $db->single('SELECT id FROM uploads WHERE file = ?', $filename);
		} while (! is_null($dbCheck));

		// Save the file
		if ( $file->save( getenv('UPLOAD_DIR') . '/' . $filename) ) {
			// Gather the required params
			$params = [
				$app->request->params('uploadToken'),
				$filename,
				$app->request->params('flowFilename'),
				isset($_FILES['file']['type']) ? $_FILES['file']['type'] : 'application/octet-stream',
			];

			// Save to the DB
			$db->query("
				INSERT INTO `uploads` (token, file, original, mime, created_at)
				VALUES (?, ?, ?, ?, NOW())
			", $params);

			$app->response->setStatus(200);
		} else {
			// Could not save file - error
			$app->halt(500);
		}
	}
	// This is not a final chunk, continue to upload
})->via(['GET','POST']);

/**
 * Contact details
 */
$app->post('/contact', function() use($app){
	// Minimal input validation - will adjust if spam becomes a problem.
	$email   = $app->request->params('email');
	$subject = $app->request->params('subject');
	$body    = $app->request->params('body');

	// This shouldn't happen if the user has JS enabled. If they don't they've
	// submitted via some odd method (the form is not visible without JS)
	if (empty($email) || empty($body)) {
		echo json_encode(['success' => false]);
		return;
	}

	// Grab the files that were uploaded
	$db      = $app->db;
	$token   = $app->request->params('uploadToken');
	$files   = $db->get("SELECT * FROM `uploads` WHERE token = ?", $token);
	$baseUrl = $app->request->getUrl();

	// Create a url for each file
	$files = array_map(function($file) use ($app, $baseUrl){
		$file['url'] = $baseUrl . $app->urlFor('file', ['id' => $file['file']]);

		return $file;
	}, $files);

	$content = $app->view->fetch('partials/contact.email.php', [
		'email'   => $email,
		'subject' => $subject,
		'body'    => $body,
		'files'   => $files,
	]);

	// Init the Mailgun API
	$mg = $app->mailgun;

	$mg->sendMessage(getenv('MAILGUN_DOMAIN'), array(
		'from'    => getenv('MAILGUN_FROM'),
		'to'      => 'rosskuyper@gmail.com',
		'subject' => 'New contact from the JVD website',
		'text'    => $content
	));

	echo json_encode(['success' => true]);
});

/**
 * Downloads
 */
$app->get('/file/:id', function($id) use ($app){
	$query = $app->db->query("SELECT * FROM `uploads` WHERE file = ?", [$id]);

	if ($file = $query->fetch()) {
		// Try keep most of the original file. First replace spaces with underlines, then remove no alphanum.
		$filename = str_replace(' ', '_', $file['original']);
		$filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);

		$app->response->headers->set('Content-Type', $file['mime']);
		$app->response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
		$app->response->headers->set('X-Accel-Redirect', '/protected/' . $file['file']);
	} else {
		$app->halt(404, "File not found");
	}
})->name('file');

$app->run();
