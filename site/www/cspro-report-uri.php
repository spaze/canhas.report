<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$cspHeader = "Content-Security-Policy-Report-Only: default-src https: 'unsafe-inline'; report-uri " . \Can\Has\reportUrl();;
header($cspHeader);

echo \Can\Has\pageHead('CSPRO report-uri &ndash; mixed content detection');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Content Security Policy <em>Report-Only</em> with <code>report-uri</code> &ndash; mixed content detection</h1>
	<p><em>
		Loading images, executing JavaScript and everything else as usual but sending a Content Security Policy (CSP) violation report (with <code>"disposition": "report"</code> instead of <code>"disposition": "enforce"</code>) if something would be loaded from HTTP, not HTTPS.<br><br>
		CSP is a policy that lets the authors (or server administrators) of a web application inform the browser about the sources from which the application expects to load resources like images, scripts, styles, or even where to submit forms.
		This Report-Only mode works with both <code>report-uri</code> and <code>report-to</code> directives, and is usually used for policy upgrades &ndash; an app can send both <code>Content-Security-Policy</code> and <code>Content-Security-Policy-Report-Only</code> headers with different policies.
	</em></p>
	<h2>The CSPRO (CSP Report-Only) response header:</h2>
	<pre><code class="csp"><?= htmlspecialchars($cspHeader); ?></code></pre>
	<ul>
		<li>
			<code>default-src</code>: what's allowed by default, includes images, fonts, JavaScript <a href="https://www.w3.org/TR/CSP3/#directive-default-src">and more</a>
			<ul>
				<li><code>https:</code> means HTTPS scheme only</li>
				<li><code>'unsafe-inline'</code> means JavaScript, CSS inlined right in the HTML source code, not in external files (e.g. code between <code>&lt;script&gt;</code> and <code>&lt;/script&gt;</code>, handlers like <code>onmouseover</code> etc.)</li>
			</ul>
		</li>
		<li><code>report-uri</code>: where to send violation reports to</li>
	</ul>

	<h2>Image mixed content</h2>
	<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
	<img src="http://www.michalspacek.cz/i/images/photos/michalspacek-trademark-400x268.jpg" width="100" height="67" alt="Loaded image">
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li>
			<span class="allowed">allowed</span> even though the image source points to <em><strong>http://</strong>www.michalspacek.cz</em> and not to <em><strong>https://</strong>www.michalspacek.cz</em>
			<ul>
				<li><small>my site supports HTTP Strict Transport Security (HSTS) so the request would be eventually auto-upgraded to HTTPS in browsers that support HSTS but CSP comes first</small></li>
				<li><small>Chrome <a href="https://www.chromestatus.com/feature/4926989725073408">plans</a> to auto-upgrade all image mixed content</small></li>
			</ul>
		</li>
		<li>would be blocked if the policy was <em>enforced</em> and not <em>report-only</em></li>
		<li>will trigger a report, check <em>Developer tools</em> (<em>Network</em> and <em>Console</em> tabs)</li>
		<li>check your <a href="<?= htmlspecialchars(\Can\Has\reportOrigin()); ?>/">reports</a></li>
	</ul>
</div>
</body>
