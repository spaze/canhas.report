<?php
require __DIR__ . '/../shared/functions.php';

if (\Can\Has\who() !== null) {
	\Can\Has\redirectToBase();
}
?>
<?= \Can\Has\pageHead(); ?>
<body>
<div id="tom">
	<h1><small>Minority</small> Reporting API Demos</h1>
	<picture>
		<source srcset="tom/minority.webp" type="image/webp">
		<img src="tom/minority.gif" alt="Minority Report(s)" width="444" height="202">
	</picture>
	<p>Michal Špaček <small>(not me⤴)</small> &mdash;&nbsp;<code>@spazef0rze</code> &mdash;&nbsp;www.michalspacek.cz</p>
</div>

<div>
<?= \Can\Has\bookmarks('reports'); ?>
<h2>Browser Reporting</h2>
<?= \Can\Has\reportingApiNotSupportedHtml('the following reports will not be sent: <abbr title="Content Security Policy">CSP</abbr> <code>report-to</code>, Crash, Deprecation, Intervention, <abbr title="Network Error Logging">NEL</abbr>') ?>
<p>
	This is a live reporting demo tool. You'll not dive deep into the technologies mentioned below, instead we'll focus on the reporting side of things.
	The headers shown here are real headers sent by the server and received by your browser, with real values. You can click around and see how things behave and how reporting works.
	These demos also work on smaller screens and mobile devices, although sometimes it might be handy to use a desktop browser to open developer tools (F12, Ctrl/Cmd+Shift+I) and watch the Console and Network tabs.
</p>
<p>
	In Chrome, you can also use <a href="chrome://net-export/">chrome://net-export/</a> (copy/paste the link) to see "hidden" asynchronous reports in exported logs.
	See my <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header">article about how to read the logs</a>.
</p>
<ol>
	<li><a href="csp-report-uri.php">Content Security Policy <code>report-uri</code></a></li>
	<li><a href="csp-report-to.php">Content Security Policy <code>report-to</code></a></li>
	<li><a href="cspro-report-uri.php">CSP Report-Only <code>report-uri</code> &ndash; mixed content detection</a></li>
	<li><a href="cspro-report-to.php">CSP Report-Only <code>report-to</code></a></li>
	<li><a href="crash.php">Crash</a></li>
	<li><a href="deprecation.php">Deprecation</a></li>
	<li><a href="intervention.php">Intervention</a></li>
	<li><a href="nel.php">Network Error Logging</a></li>
	<li><a href="expect-ct.php">Expect-CT</a></li>
</ol>

<h3>Removed Browser Reporting</h3>
<p>Browsers used to send some reports but don't anymore as these features have been (mostly) removed:</p>
<ul>
	<li>
		<em>XSS Auditor</em>: designed to help stop <em>Reflected Cross-Site Scripting</em> attacks but often exploited to extract information from pages.
		Introduced in Internet Explorer 8 back in 2009 as <em>XSS Filter</em> and in Chrome 4 in 2010, controlled by the <code>X-XSS-Protection</code> HTTP header.
		The feature was completely <a href="https://www.chromestatus.com/feature/5021976655560704">removed in Chrome 78</a>
		and in <a href="https://blogs.windows.com/windowsexperience/2018/07/25/announcing-windows-10-insider-preview-build-17723-and-build-18204/">Microsoft Edge in 2018</a>.
		Chrome was the only browser with XSS Auditor reporting (enabled with the <code>report</code> field in the <code>X-XSS-Protection: 1; report=&lt;url&gt;</code> header).
	</li>
</ul>

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
	<li><a href="https://source.chromium.org/chromium/chromium/src/+/master:net/network_error_logging/network_error_logging_service.cc?q=kErrorTypes">All NEL types</a></li>
	<li><a href="https://source.chromium.org/chromium/chromium/src/+/master:third_party/blink/renderer/core/frame/deprecation.cc?q=GetDeprecationInfo">All Chrome deprecations and invalid features</a></li>
	<li><a href="https://www.chromestatus.com/features#intervention">Chrome interventions, incomplete list</a></li>
</ul>

<h2>Specs</h2>
<ul>
	<li>
		<a href="https://www.w3.org/TR/CSP3/">Content Security Policy Level 3</a> Working Draft
		<ul>
			<li><small><a href="https://www.w3.org/TR/CSP2/">Content Security Policy Level 2</a></small></li>
			<li><small><a href="https://www.w3.org/TR/CSP1/">Content Security Policy 1.0</a> (discontinued)</small></li>
		</ul>
	</li>
	<li>
		<a href="https://www.w3.org/TR/reporting/">Reporting API</a> Working Draft
		<ul>
			<li><small><a href="https://w3c.github.io/reporting/">Reporting API</a> Editor's Draft (which will evolve into a Working Draft, followed by a Recommendation eventually)</small></li>
			<li><small>
				Notable changes in the Editor's Draft are switching to structured headers (<code>Reporting-Endpoints</code> instead of <code>Report-To</code>) and moving out concrete reports into the following separate Draft Community Group Reports:
				<a href="https://wicg.github.io/crash-reporting/">Crash Reporting</a>,
				<a href="https://wicg.github.io/deprecation-reporting/">Deprecation Reporting</a>,
				<a href="https://wicg.github.io/intervention-reporting/">Intervention Reporting</a>
			</small></li>
		</ul>
	</li>
	<li><a href="https://www.w3.org/TR/network-error-logging/">Network Error Logging</a> Working Draft</li>
	<li><a href="https://tools.ietf.org/html/draft-ietf-httpbis-expect-ct">Expect-CT Extension for HTTP</a> Internet-Draft</li>
	<li><a href="https://tools.ietf.org/html/rfc8659">DNS Certification Authority Authorization (CAA) Resource Record</a> (RFC 8659)</li>
	<li><a href="https://tools.ietf.org/html/rfc7489">Domain-based Message Authentication, Reporting, and Conformance (DMARC)</a> (RFC 7489)</li>
	<li><a href="https://tools.ietf.org/html/rfc8460">SMTP TLS Reporting</a> (RFC 8460)</li>
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
