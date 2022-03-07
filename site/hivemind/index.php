<?php
declare(strict_types = 1);

require __DIR__ . '/../reports/config.php';
require __DIR__ . '/../shared/functions.php';

$baseOrigin = \Can\Has\baseOrigin();
header("Content-Security-Policy: default-src 'none'; script-src {$baseOrigin}; img-src {$baseOrigin}; style-src {$baseOrigin}; base-uri 'none'; form-action 'none'");

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$statement = $database->prepare('DELETE FROM reports WHERE received < ?')->execute([(new DateTime('-' . \Can\Has\dataRetentionDays() . ' days'))->format('Y-m-d H:i:s')]);
$statement = $database->prepare('SELECT received, types, report, who FROM reports ORDER BY received DESC');
$statement->execute();

$seen = null;
$reportsHtml = \Can\Has\reports($statement, $seen);
if ($seen) {
	\Can\Has\setCookie('seen', (string)$seen, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('All Received Reports'); ?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index'); ?>
	<h1>All Received Reports</h1>
	<?= $reportsHtml; ?>
	<p><a href="<?= htmlspecialchars($baseOrigin) ?>">↩ Back</a></p>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
