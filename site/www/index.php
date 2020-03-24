<?php
require __DIR__ . '/../shared/functions.php';

if (\Can\Has\who() !== null) {
	\Can\Has\redirectToBase();
}
?>
<?= \Can\Has\pageHead(); ?>
<body>
<div id="tom">
	<h1>Can Has (Minority) Reporting?</h1>
	<picture>
		<source srcset="tom/minority.webp" type="image/webp">
		<img src="tom/minority.gif" alt="Minority Report(s)" width="444" height="202">
	</picture>
	<p>Michal Špaček <small>(not me⤴)</small> &mdash; <code>@spazef0rze</code> &mdash; www.michalspacek.cz</p>
</div>

<div>
<?= \Can\Has\bookmarks('reports'); ?>
<h2>Browser Reporting</h2>
<div class="not-supported hidden">
	😥 Your browser <a href="https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API#Browser_compatibility">does not support</a> Reporting API,
	the following reports will not be sent: <abbr title="Content Security Policy">CSP</abbr> <code>report-to</code>, Crash, Deprecation, Intervention, <abbr title="Network Error Logging">NEL</abbr>
</div>
<p>
	Open developer tools (F12, Ctrl/Cmd+Shift+I) and watch the Console and Network tabs.
	Also use <a href="chrome://net-export/">chrome://net-export/</a> (copy/paste the link) to see "hidden" asynchronous reports in exported logs.
	See my <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header">article about how to read the logs</a>.
</p>
<ol>
	<li><a href="csp-report-uri.php">Content Security Policy <code>report-uri</code></a></li>
	<li><a href="csp-report-to.php">Content Security Policy <code>report-to</code></a></li>
	<li><a href="cspro-report-to.php">Content Security Policy Report-Only <code>report-to</code></a></li>
	<li><a href="crash.php">Crash</a></li>
	<li><a href="deprecation.php">Deprecation</a></li>
	<li><a href="intervention.php">Intervention</a></li>
	<li><a href="nel.php?do=404">Network Error Logging 404</a></li>
	<li><a href="nel.php?do=wronghost">Network Error Logging TLS cert wrong host</a></li>
	<li><a href="xss-auditor.php">XSS Auditor</a></li>
	<li><a href="expect-ct.php">Expect-CT</a></li>
</ol>

<h2>Certification Authorities</h2>
<ol>
	<li><a href="https://toolbox.googleapps.com/apps/dig/#CAA/michalspacek.cz">Certification Authority Authorization (CAA)</a> <em>iodef</em> in DNS</li>
</ol>

<h2>Email Reporting</h2>
<ol>
	<li><a href="https://toolbox.googleapps.com/apps/dig/#TXT/_dmarc.michalspacek.cz">Domain-based Message Authentication, Reporting and Conformance (DMARC)</a> <em>rua</em>, <em>ruf</em> in DNS</li>
	<li><a href="https://toolbox.googleapps.com/apps/dig/#TXT/_smtp._tls.michalspacek.cz">SMTP TLS Reporting</a> <em>rua</em> in DNS</li>
</ol>

<h2>Meta</h2>
<ul>
	<li><a href="<?= htmlspecialchars(\Can\Has\reportOrigin()); ?>/">View your reports</a></li>
	<li>Your reporting subdomain is <code><a href="<?= htmlspecialchars(\Can\Has\reportOrigin()); ?>/"><?= htmlspecialchars(\Can\Has\cookie()) ?></a></code></li>
	<li><a href="https://github.com/spaze/canhas.report/tree/master/site">Source code</a></li>
	<li><a href="https://cs.chromium.org/chromium/src/net/network_error_logging/network_error_logging_service.cc?l=78-139">All NEL types</a></li>
</ul>

<h2>Tools</h2>
<ul>
	<li><a href="https://report-uri.com/">report-uri.com</a> Browser reporting aggregator ← I work on this one</li>
	<li><a href="https://hardenize.com/">hardenize.com</a> Security setting/headers tester ← I don't work on these</li>
	<li><a href="https://observatory.mozilla.org/">observatory.mozilla.org</a> Another one</li>
	<li><a href="https://securityheaders.com/">securityheaders.com</a> Yet another one</li>
</ul>

<p><em>By <a href="https://www.michalspacek.cz">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">spazef0rze</a></em></p>
</div>
</body>
