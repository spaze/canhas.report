<?php
declare(strict_types = 1);

// Next time use a framework dude

require __DIR__ . '/../shared/functions.php';

$requestUri = preg_replace('~\?.*$~', '', $_SERVER['REQUEST_URI']);
$pathinfo = pathinfo($requestUri === '/' ? '/index' : $requestUri);
if (isset($pathinfo['extension']) && $pathinfo['extension'] === 'php') {
	header('Location: /' . $pathinfo['filename'], true, 301);
	echo 'Redirecting to <a href="/' . htmlspecialchars($pathinfo['filename']) . '">/' . htmlspecialchars($pathinfo['filename']) . '</a>';
	exit();
}

$file = __DIR__ . '/../pages/' . $pathinfo['filename'] . '.php';
if (is_readable($file)) {
	require $file;
} else {
	http_response_code(404);
	echo \Can\Has\pageHead('Not Found');
?>
	<body>
	<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
	<div>
		<?= \Can\Has\bookmarks('index', 'reports'); ?>
		<h1>Page Not Found 🙊🙈🙉</h1>
		<p>Don't be sad though and watch Minority Report trailer instead</p>
		<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/aGWQYgZZEEQ" frameborder="0"></iframe>
	</div>
	<?= \Can\Has\footerHtml(); ?>
	</body>
<?php
}
