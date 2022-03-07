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

$seen = null;
$reportsHtml = \Can\Has\reports($statement, $seen);
if ($seen) {
	\Can\Has\setCookie('seen', (string)$seen, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Received Reports'); ?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div id="reports">
<div class="content">
<?= \Can\Has\bookmarks('index'); ?>
<h1>Received Reports</h1>
<p>
	Want better reporting and graphs? Try <?= \Can\Has\smallReportUriLogoHtml(); ?>
	&mdash; <a href="https://report-uri.com/register">sign up</a> and send your test reports there! <a href="<?= htmlspecialchars($baseOrigin) ?>#reports">How?</a>
</p>
<?= $reportsHtml; ?>
<p><a href="<?= htmlspecialchars($baseOrigin) ?>">↩ Back</a></p>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
