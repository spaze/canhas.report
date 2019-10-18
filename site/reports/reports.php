<?php
declare(strict_types = 1);

require __DIR__ . '/config.php';
require __DIR__ . '/../shared/functions.php';

$baseOrigin = \Can\Has\baseOrigin();
header("Content-Security-Policy: default-src 'none'; script-src {$baseOrigin}; style-src {$baseOrigin}; base-uri 'none'; form-action 'none'");

$who = \Can\Has\who();
if ($who === null) {
	\Can\Has\redirectToBase();
}

$database = new PDO("mysql:host=$dbHost;dbname=$dbSchema", $dbUsername, $dbPassword);
$statement = $database->prepare('SELECT received, types, report FROM reports WHERE who = ? ORDER BY received DESC');
$statement->execute([$who]);
echo \Can\Has\pageHead('Received Reports', ['highlight.pack.js', 'highlight-init.js']);
?>
<body>
<div id="reports">
<?= \Can\Has\bookmarks('index'); ?>
<h1>Received Reports</h1>
<?php
foreach ($statement as $row) {
	$counts = array_count_values(json_decode($row['types']));
	$types = [];
	foreach ($counts as $type => $count) {
		$types[] = "{$count}× $type";
	}
	$reports = json_decode($row['report']);

	$json = urldecode(json_encode($reports, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
	printf('<p>%s <strong>%s</strong>%s</p><pre><code class="json">%s</code></pre>',
		htmlspecialchars($row['received']),
		htmlspecialchars(implode(' + ', $types)),
		(is_array($reports) ? ' via <code>Report-To</code>' : ''),
		htmlspecialchars(preg_replace('/^(  +?)\\1(?=[^ ])/m', '$1', $json))
	);
}

if ($statement->rowCount() === 0) {
	echo '<p>No reports yet</p>';
}
?>
<p><a href="<?= htmlspecialchars($baseOrigin) ?>">↩ Back</a> <em>By <a href="https://www.michalspacek.cz">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">spazef0rze</a></em></p>
</div>
