<?php
declare(strict_types = 1);

require __DIR__ . '/../reports/config.php';
require __DIR__ . '/../shared/functions.php';

$baseOrigin = \Can\Has\baseOrigin();
header("Content-Security-Policy: default-src 'none'; script-src {$baseOrigin}; img-src {$baseOrigin}; style-src {$baseOrigin}; base-uri 'none'; form-action 'none'");

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$statement = $database->prepare('SELECT received, types, report, who FROM reports ORDER BY received DESC');
$statement->execute();
echo \Can\Has\pageHead('All Received Reports');
?>
<body>
<div id="reports">
	<?= \Can\Has\bookmarks('index'); ?>
	<h1>All Received Reports</h1>
	<?= \Can\Has\reports($statement); ?>
	<p><a href="<?= htmlspecialchars($baseOrigin) ?>">↩ Back</a></p>
	<?= \Can\Has\footerHtml(); ?>
</div>
</body>
