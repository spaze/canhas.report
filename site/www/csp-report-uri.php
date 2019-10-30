<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; report-uri " . \Can\Has\reportUrl());
echo \Can\Has\pageHead('CSP report-uri');
?>
<body>
<div>
<?= \Can\Has\bookmarks('index', 'reports'); ?>
<img src="https://www.michalspacek.cz/i/images/photos/michalspacek-trademark-400x268.jpg" width="100" height="67">
<br><br>
then
<br><br>

<script nonce="<?= htmlspecialchars($nonce); ?>">
	console.log('hi');
</script>

<button id="inject">Inject 3VIL JS</button>
<script nonce="<?= htmlspecialchars($nonce); ?>">
document.getElementById('inject').onclick = function() {
	var script = document.createElement('script');
	script.text = 'console.log("lo")';
	document.getElementById('inject').appendChild(script);
}
</script>
</div>
</body>