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

$nel = [
	'report_to' => 'default',
	'max_age' => 60,
	'include_subdomains' => true,
];
// $nel['success_fraction' => 0.5];  // 0.0-1.0
// $nel['failure_fraction' => 0.5];
header('NEL: ' . json_encode($nel, JSON_UNESCAPED_SLASHES));

switch ($_GET['do'] ?? '') {
	case '404':
		http_response_code(404);
		echo \Can\Has\pageHead('NEL 404');
		echo \Can\Has\pageBody('Not Found');
		break;
	case 'wronghost':
		echo \Can\Has\pageHead('NEL tls.cert.name_invalid');
		echo \Can\Has\pageBody('Go to: <a href="https://wrong.host.canhas.report/">https://wrong.host.canhas.report/</a>');
		break;
	default:
		echo \Can\Has\pageHead('NEL?');
		echo \Can\Has\pageBody('Can do: 404 wronghost');
		break;
}
