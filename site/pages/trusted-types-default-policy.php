<?php
declare(strict_types = 1);

$cspHeader = "Content-Security-Policy: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce');
header($cspHeader);

echo \Can\Has\pageHead('Require Trusted Types with CSP');
?>
<body>
<?= \Can\Has\headerHtml('DOM-XSS Prevention with Trusted Types and CSPRO'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Prevent DOM-based XSS with Trusted Types and Content Security Policy with <code>report-uri</code></h1>
	<?= \Can\Has\trustedTypesNotSupportedHtml(); ?>
	<h2>The CSP response header:</h2>
	<pre><code><?= \Can\Has\highlight($cspHeader); ?></code></pre>

	<h2>Trusted Types with a default policy</h2>
	<p>
		<button id="prompt-html" class="schroedingers-cat">Enter any HTML</button>
		<span id="html-me"></span>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script>
			trustedTypes.createPolicy('default', {
				createHTML: string => string.replaceAll('<', '&lt;'),
			});
			document.getElementById('prompt-html').onclick = function() {
				const html = prompt('Enter any HTML', 'foo <strong>bar</strong>');
				if (html) {
					document.getElementById('html-me').innerHTML = html;
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>

	<?= \Can\Has\specsHtml('trusted-types'); ?>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
