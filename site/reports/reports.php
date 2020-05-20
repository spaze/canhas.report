<?php
declare(strict_types = 1);

require __DIR__ . '/config.php';
require __DIR__ . '/../shared/functions.php';

$baseOrigin = \Can\Has\baseOrigin();
header("Content-Security-Policy: default-src 'none'; script-src {$baseOrigin}; img-src {$baseOrigin}; style-src {$baseOrigin}; base-uri 'none'; form-action 'none'");

$who = \Can\Has\who();
if ($who === null) {
	\Can\Has\redirectToBase();
}

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$statement = $database->prepare('SELECT received, types, report FROM reports WHERE who = ? ORDER BY received DESC');
$statement->execute([$who]);
echo \Can\Has\pageHead('Received Reports');
?>
<body>
<div id="reports">
<?= \Can\Has\bookmarks('index'); ?>
<h1>Received Reports</h1>
<?= \Can\Has\reports($statement); ?>
<p><a href="<?= htmlspecialchars($baseOrigin) ?>">↩ Back</a></p>
<?= \Can\Has\footerHtml(); ?>
</div>
