<?php
declare(strict_types = 1);

require __DIR__ . '/functions.php';

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
?>
Crash the tab: <a href="chrome://crash/">chrome://crash/</a> (copy and paste the link)
