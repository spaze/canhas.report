<?php
declare(strict_types = 1);

$cspHeader = "Content-Security-Policy-Report-Only: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce');
header($cspHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Require Trusted Types with CSPRO'); ?>
<body>
<?= \Can\Has\headerHtml('DOM-XSS Injection Sinks Detection with Trusted Types and CSPRO'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>DOM-based XSS Injection Sinks Detection with Trusted Types and Content Security Policy with <code>report-uri</code></h1>
	<?= \Can\Has\trustedTypesNotSupportedHtml(); ?>
	<h2>The CSPRO (CSP Report-Only) response header:</h2>
	<pre><code><?= \Can\Has\highlight($cspHeader); ?></code></pre>

	<h2>DOM-based XSS</h2>
	<p>
		<button id="prompt-xss" class="schroedingers-cat">Enter any HTML</button>
		<span id="xss-me"></span>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script>
			document.getElementById('prompt-xss').onclick = function() {
				const html = prompt('Enter any HTML', 'foo <strong>bar</strong>');
				if (html) {
					document.getElementById('xss-me').innerHTML = html;
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>

	<?= \Can\Has\specsHtml('trusted-types'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
