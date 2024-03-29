<?php
declare(strict_types = 1);

$cspHeader = "Content-Security-Policy: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce');
header($cspHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Require Trusted Types with CSP');  ?>
<body>
<?= \Can\Has\headerHtml('DOM-XSS Prevention with Trusted Types and CSPRO'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Prevent DOM-based XSS with Trusted Types and Content Security Policy with <code>report-uri</code></h1>
	<?= \Can\Has\trustedTypesNotSupportedHtml(); ?>
	<h2>The CSP response header:</h2>
	<pre><code><?= \Can\Has\highlight($cspHeader); ?></code></pre>

	<h2>Trusted Types with a custom policy</h2>
	<p>
		<button id="prompt-html" class="schroedingers-cat">Enter any HTML</button>
		<span id="html-me"></span>
		<span id="xss-me"></span>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script>
			const escapePolicy = trustedTypes.createPolicy('escapePolicy', {
				createHTML: string => string.replaceAll('<', '&lt;'),
			});
			document.getElementById('prompt-html').onclick = function() {
				const html = prompt('Enter any HTML', 'foo <strong>bar</strong>');
				if (html) {
					document.getElementById('html-me').innerHTML = escapePolicy.createHTML(html);
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
