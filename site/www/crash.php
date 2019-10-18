<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$reportTo = [
	'group' => 'default',
	'max_age' => 60,
	'endpoints' => [
		[
			'url' => \Can\Has\reportUrl(),
		]
	],
	'include_subdomains' => true,
];
header('Report-To: ' . json_encode($reportTo, JSON_UNESCAPED_SLASHES));
echo \Can\Has\pageHead('Crash');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
Crash the tab: <a href="chrome://crash/">chrome://crash/</a> (copy and paste the link)
</div>
</body>
