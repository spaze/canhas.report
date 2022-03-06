<?php
declare(strict_types = 1);

$nonce = \Can\Has\randomNonce();
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; form-action 'self'; report-to default";
$reportToHeader = \Can\Has\reportToHeader();
header($cspHeader);
header($reportToHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('More CSP reports'); ?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>More Content Security Policy with <code>report-to</code></h1>
	<p><em>Sending even more Content Security Policy (CSP) violation reports with <code>report-to</code>, asynchronously and possibly grouping more reports together. Read <a href="csp-report-to">general CSP reporting</a> description for more details.</em></p>

	<h2>The CSP response header:</h2>
	<pre><code><?= \Can\Has\highlight($cspHeader); ?></code></pre>
	<ul>
		<li><code>default-src</code>: what's allowed by default, includes images, fonts, JavaScript <a href="https://www.w3.org/TR/CSP3/#directive-default-src">and more</a></li>
		<li>
			<code>img-src</code>: where to load images from, overrides <code>default-src</code> for images
			<ul>
				<li><code>'self'</code> means current URL's origin (scheme + host + port)</li>
			</ul>
		</li>
		<li>
			<code>script-src</code>: what JavaScript is allowed to be executed
			<ul>
				<li><code>'nonce-<?= htmlspecialchars($nonce); ?>'</code> means <code>script</code> elements with <code>nonce="<?= htmlspecialchars($nonce); ?>"</code> attribute</li>
				<li><code>'report-sample'</code> instructs the browser to include the first 40 characters of the blocked JavaScript in the report</li>
			</ul>
		</li>
		<li><code>style-src</code>: allowed CSS sources</li>
		<li><code>form-action</code>: where it's allowed to submit forms, not part of <code>default-src</code></li>
		<li><code>report-to</code>: name of the group where to send violation reports to</li>
	</ul>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'the same as in the CSP header in the <code>report-to</code> directive'); ?>

	<h2>Load any image</h2>
	<p>
		<button id="prompt-image" class="schroedingers-cat">Load an image by URL</button>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('prompt-image').onclick = function() {
				const url = prompt('Enter a URL to be used for an image', 'https://');
				if (url) {
					new Image().src = url;
					alert('Tried loading an image from ' + url);
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="allowed">Allowed</span> or <span class="blocked">blocked</span> depending on what URL you enter, only some images are allowed to load, see the CSP header above</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?> &ndash; if blocked, a report will be sent</li>
	</ul>

	<h2>&hellip; any JavaScript file</h2>
	<p>
		<button id="prompt-javascript" class="schroedingers-cat">Load a JavaScript by URL</button>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('prompt-javascript').onclick = function() {
				const url = prompt('Enter a URL to load a JS file from', 'https://');
				if (url) {
					const script = document.createElement('script');
					script.src = url;
					document.getElementById('prompt-javascript').insertAdjacentElement('afterend', script);
					alert('Tried loading a JS file from ' + url);
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="allowed">Allowed</span> or <span class="blocked">blocked</span> depending on what URL you enter, only some JS is allowed to load, see the CSP header above</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?> &ndash; if blocked, a report will be sent</li>
	</ul>

	<h2>&hellip; any CSS file</h2>
	<p>
		<button id="prompt-css" class="schroedingers-cat">Load a CSS by URL</button>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('prompt-css').onclick = function() {
				const url = prompt('Enter a URL to load a CSS file from', 'https://');
				if (url) {
					const link = document.createElement('link');
					link.rel = 'stylesheet';
					link.href = url;
					document.getElementById('prompt-javascript').insertAdjacentElement('afterend', link);
					alert('Tried loading a CSS file from ' + url);
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="allowed">Allowed</span> or <span class="blocked">blocked</span> depending on what URL you enter, only some CSS is allowed to load, see the CSP header above</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?> &ndash; if blocked, a report will be sent</li>
	</ul>

	<h2>Submit a form anywhere</h2>
	<p>
		With <code>form-action</code>, you can limit where forms on your page can be submitted,
		so if an attacker would inject a fake form or would change the <code>action</code> of your existing form, the browser wouldn't submit it.
		Please note that <code>form-action</code> is not part of the <code>default-src</code> fallback, and needs to be explicitly specified if you want to limit where forms are to be submitted.
	</p>
	<p>
		<button id="prompt-form" class="schroedingers-cat">Submit a form to a URL</button>
		<?php \Can\Has\scriptSourceHtmlStart('schroedingers-cat'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('prompt-form').onclick = function() {
				const url = prompt('Enter a URL where to submit a form to', 'https://');
				if (url) {
					const form = document.getElementById('submitForm');
					if (form) {
						form.setAttribute('action', url);
						form.submit();
					} else {
						const form = document.createElement('form');
						form.setAttribute('method', 'post');
						form.setAttribute('class', 'hidden');
						form.setAttribute('action', url);
						form.setAttribute('id', 'submitForm');
						const text = document.createElement('input');
						text.setAttribute('type', 'text');
						text.setAttribute('value', 'text value');
						form.appendChild(text);
						const hidden = document.createElement('input');
						hidden.setAttribute('type', 'hidden');
						hidden.setAttribute('value', 'hidden value');
						form.appendChild(hidden);
						document.getElementById('prompt-form').insertAdjacentElement('afterend', form);
						form.submit();
					}
					alert('Submitted a form to ' + url);
				}
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="allowed">Allowed</span> or <span class="blocked">blocked</span> depending on what URL you enter, forms are not allowed to be submitted anywhere, see the CSP header above</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?> &ndash; if blocked, a report will be sent</li>
	</ul>

	<?= \Can\Has\specsHtml('csp', 'reporting-api'); ?>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
