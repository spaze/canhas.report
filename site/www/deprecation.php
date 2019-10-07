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
?>

<button id="xhr">Synchronous <code>XMLHttpRequest</code></button>
<script>
document.getElementById('xhr').onclick = function() {
	var client = new XMLHttpRequest();
	client.open('GET', 'foo', false);
}	
</script>
