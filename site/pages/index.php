<?php
declare(strict_types = 1);

if (\Can\Has\who() !== null) {
	\Can\Has\redirectToBase();
}
?>
<?= \Can\Has\pageHead(); ?>
<body>
<div id="tom">
	<h1><small>Minority</small> Reporting API Demos</h1>
	<picture>
		<source srcset="<?= htmlspecialchars(\Can\Has\baseOrigin()); ?>/assets/minority.webp" type="image/webp">
		<img src="<?= htmlspecialchars(\Can\Has\baseOrigin()); ?>/assets/minority.gif" alt="Minority Report(s)" title="This is not Scott Helme" width="444" height="202">
	</picture>
	<p id="supported-by">
		<a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener">
			<strong>Supported by</strong>
			<img src="<?= htmlspecialchars(\Can\Has\baseOrigin()); ?>/assets/report-uri.svg" alt="report-uri.com logo" width="152" height="27">
		</a>
	</p>
</div>

<div>
<?= \Can\Has\bookmarks('reports'); ?>
<h2>Browser Reporting</h2>
<?= \Can\Has\reportingApiNotSupportedHtml('the following reports will not be sent: <abbr title="Content Security Policy">CSP</abbr> <code>report-to</code>, Crash, Deprecation, Intervention, <abbr title="Network Error Logging">NEL</abbr>') ?>
<p>
	This is a live reporting demo tool. Although you'll dive deep into the technologies mentioned below, you'll not dive <em>very deep</em>, and instead, we'll focus on the reporting side of things.
	The headers shown here are real headers sent by the server and received by your browser, with real values. You can click around and see how things behave and how reporting works.
	These demos also work on smaller screens and mobile devices, although sometimes it might be handy to use a desktop browser to open developer tools (F12, Ctrl/Cmd+Shift+I) and watch the Console and Network tabs.
	In Chrome, you can also use <a href="chrome://net-export/">chrome://net-export/</a> (copy/paste the link) to see "hidden" asynchronous reports in exported logs.
	See my <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header">article about how to read the logs</a>.
</p>
<p>Not all of these use Reporting API, some are proprietary reporting mechanisms and you'll notice them easily &ndash; they don't use the <code>Report-To</code> header.</p>
<ol>
	<li><a href="csp-report-uri">Content Security Policy <code>report-uri</code></a></li>
	<li><a href="csp-report-to">Content Security Policy <code>report-to</code></a></li>
	<li><a href="csp-urls">More CSP <code>report-to</code> &ndash; load resources by specified URL, submit forms</a></li>
	<li><a href="cspro-report-uri">CSP Report-Only <code>report-uri</code> &ndash; mixed content detection</a></li>
	<li><a href="cspro-report-to">CSP Report-Only <code>report-to</code></a></li>
	<li><a href="crash">Crash</a></li>
	<li><a href="deprecation">Deprecation</a></li>
	<li><a href="intervention">Intervention</a></li>
	<li><a href="nel">Network Error Logging</a></li>
	<li><a href="expect-ct">Expect-CT</a></li>
</ol>

<h3>Removed Browser Reporting</h3>
<p>Browsers used to send some reports but don't anymore as these features have been (mostly) removed:</p>
<ul>
	<li><a href="xss-auditor">XSS Auditor</a></li>
	<li><a href="hpkp">HTTP-based Public Key Pinning</a></li>
</ul>

<h2>Other Reporting</h2>
<ol>
	<li><a href="caa">Certification Authority Authorization (CAA)</a></li>
	<li><a href="dmarc">Domain-based Message Authentication, Reporting and Conformance (DMARC)</a></li>
	<li><a href="smtp-tlsrpt">SMTP TLS Reporting (SMTP TLSRPT)</a></li>
</ol>

<h2>Your Reports</h2>
<ul>
	<li>
		<a href="<?= htmlspecialchars(\Can\Has\reportViewer()); ?>/">View your reports</a>
		<?php if (!\Can\Has\reportToReportUri()) { ?>
			(kept for <?= htmlspecialchars((string)\Can\Has\dataRetentionDays()); ?> days)
		<?php } ?>
	</li>
	<?php if (\Can\Has\reportToReportUri()) { ?>
		<li>Reports sent to <a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener">Report URI</a> subdomain <code><?= htmlspecialchars(\Can\Has\cookieReportUri()) ?></code> <button id="change-endpoint" data-endpoint="<?= htmlspecialchars(\Can\Has\cookieNameEndpoint()); ?>">Change to <em>canhas.report</em></button></li>
	<?php } ?>
	<li>
		Your <em>canhas.report</em> reporting subdomain is <code><a href="<?= htmlspecialchars(\Can\Has\reportOrigin()); ?>/"><?= htmlspecialchars(\Can\Has\cookie()) ?></a></code>
		<button id="subdomain" data-cookie="<?= htmlspecialchars(\Can\Has\cookieName()); ?>" data-subdomain="<?= htmlspecialchars(\Can\Has\cookie()) ?>">Change</button>
	</li>
	<li>
		<?php if (\Can\Has\reportToReportUri()) { ?>
			To send reports to <a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener">Report URI</a> report aggregator and monitoring platform:
		<?php } else { ?>
			Instead, you can send reports to <a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener">Report URI</a> report aggregator and monitoring platform:
		<?php } ?>
		<ol>
			<li><a href="https://report-uri.com/register" target="_blank" rel="noreferrer noopener">Register</a> or <a href="https://report-uri.com/login" target="_blank" rel="noreferrer noopener">sign in</a></li>
			<li>Add <code><?= htmlspecialchars(\Can\Has\baseHostname()); ?></code> to <em>Global Report Filters</em> &gt; <em>Sites to collect reports for</em> in <a href="https://report-uri.com/account/filters/" target="_blank" rel="noreferrer noopener"><em>Filters</em></a></li>
			<li>Go to <a href="https://report-uri.com/account/setup/" target="_blank" rel="noreferrer noopener"><em>Setup</em></a> &gt; <em>Custom subdomain</em> and copy <em>Your current subdomain</em> (ends with <em>.report-uri.com</em>), you can also customize it first</li>
			<li>
				<button
					id="report-uri"
					data-cookie="<?= htmlspecialchars(\Can\Has\cookieNameReportUri()); ?>"
					data-subdomain="<?= \Can\Has\cookieReportUri() ? htmlspecialchars(\Can\Has\cookieReportUri()) : ''; ?>"
					data-endpoint="<?= htmlspecialchars(\Can\Has\cookieNameEndpoint()); ?>"
					data-endpoint-report-uri="<?= htmlspecialchars(\Can\Has\cookieValueEndpointReportUri()); ?>"
				><?= \Can\Has\reportToReportUri() && \Can\Has\cookieReportUri() ? 'Change Report URI subdomain' : 'Setup reporting to Report URI'; ?></button>
			</li>
		</ol>
	</li>
</ul>

<h2>Meta</h2>
<ul>
	<li><a href="https://github.com/spaze/canhas.report/tree/master/site">Source code</a></li>
	<li>The domain name <em>can has</em> is a reference to <a href="https://cheezburger.com/875511040/original-cat-meme-that-started-cheezburger">this meme</a></li>
</ul>

<h2>Tools</h2>
<ul>
	<li><a href="https://report-uri.com/">report-uri.com</a> Browser reporting aggregator ← I <a href="https://www.michalspacek.com/adding-features-and-deleting-code-or-how-i-joined-report-uri">work</a> on this one</li>
	<li><a href="https://securityheaders.com/">securityheaders.com</a> Security headers tester</li>
	<li>Other testers: <a href="https://hardenize.com/">hardenize.com</a>, <a href="https://observatory.mozilla.org/">observatory.mozilla.org</a></li>
</ul>

</div>
<?= \Can\Has\footerHtml(); ?>
</body>
