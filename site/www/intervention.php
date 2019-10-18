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
echo \Can\Has\pageHead('Intervention');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
<button id="wheel">Add <code>onwheel</code> handler</button> and then use your mousewheel to scroll
<script>
document.getElementById('wheel').onclick = function() {
	window.addEventListener('wheel', function(event) {
		event.preventDefault();
		console.log('weee');
	});
}
</script>
</div>
</body>
