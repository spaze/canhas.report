<?php
declare(strict_types = 1);

$cspHeader = "Content-Security-Policy-Report-Only: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce');
header($cspHeader);

echo \Can\Has\pageHead('Require Trusted Types with CSPRO', false);  // highlight.js triggers Trusted Types reports
?>
<body>
<?= \Can\Has\headerHtml('DOM-XSS Injection Sinks Detection with Trusted Types and CSPRO'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>DOM-based XSS Injection Sinks Detection with Trusted Types and Content Security Policy with <code>report-uri</code></h1>

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
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
