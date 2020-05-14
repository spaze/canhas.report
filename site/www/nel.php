<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$reportToHeader = \Can\Has\reportToHeader();
$nelHeader = \Can\Has\nelHeader();
header($reportToHeader);
header($nelHeader);

echo \Can\Has\pageHead('Network Error Logging');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Network Error Logging reports</h1>
	<p><em>
		Network Error Logging (NEL) enables web applications to declare a reporting policy that can be used by the browser to report network errors for a given origin.
		DNS resolution errors, secure connection errors, HTTP errors like 404s, redirect loops etc. But you can even get HTTP 2xx, 3xx success reports, if you wish.
	</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>
	<h2>The <code>NEL</code> response header:</h2>
	<pre><code class="json"><?= htmlspecialchars($nelHeader); ?></code></pre>
	<ul>
		<li><code>report_to</code>: name of the group where to send NEL reports to (that's an underscore, unlike in the <code>Content-Security-Policy</code> header)</li>
		<li><code>max_age</code>: the lifetime of this NEL policy in seconds, set to weeks or months eventually to also get reports from browsers that have not visited the site for some time</li>
		<li>
			<code>include_subdomains</code>: optional, whether this policy applies to all subdomains of the current domain
			<ul>
				<li><a href="https://www.w3.org/TR/network-error-logging/#privacy-considerations">used</a> only for errors that occurred during DNS resolution, not for errors that occurred in other phases (<em>connection</em>, <em>application</em>)</li>
				<li>this means that even if the browser has an <code>include_subdomains: true</code> NEL policy cached for <code>https://example.com</code>, it will not generate reports for expired certificate on <code>https://expired.example.com</code> because these errors happen in the <em>connection</em> phase</li>
				<li>but will send a report for an expired certificate on <code>https://example.com</code></li>
			</ul>
		</li>
	</ul>
	<p>The header can also contain these optional fields, both are a number between <code>0.0</code> and <code>1.0</code>:</p>
	<ul>
		<li><code>success_fraction</code>: defines a sampling rate to limit the number of "successful" reports sent by the browser, by default no such reports are sent</li>
		<li><code>failure_fraction</code>: defines a sampling rate to limit the number of "failure" reports sent by the browser, all such reports are sent by default</li>
	</ul>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'the same as in the <code>NEL</code> header'); ?>

	<h2>Generate HTTP 404</h2>
	<button id="http" class="blocked">Load a page that doesn't exist</button>, e.g.
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<code id="url"><?= htmlspecialchars(\Can\Has\baseOrigin()); ?>/<?= htmlspecialchars(\Can\Has\randomSubdomain() . '-' . \Can\Has\randomSubdomain() . '-is.404'); ?></code>
	<script>
		document.getElementById('http').onclick = function() {
			new Image().src = document.getElementById('url').textContent;
			alert('HTTP 404 generated');
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('HTTP 404'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<h2>Generate DNS resolution error</h2>
	<button id="dns" class="blocked">Send a request to a host that doesn't exist</button>, e.g.
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<code id="cheezburger"><?= htmlspecialchars(\Can\Has\baseSubdomainOrigin('cheezburger')); ?></code>
	<script>
		document.getElementById('dns').onclick = function() {
			new Image().src = document.getElementById('cheezburger').textContent;
			alert('DNS resolution error generated');
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('resolution error'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<h2>Generate TLS certificate error</h2>
	<p>
		Testing TLS NEL reports is a bit difficult and cannot be done with just a few clicks here.
		The reason is that the browser needs to cache the NEL policy for the host first, and for that it needs a working and secure HTTPS connection (i.e. no errors).
		And then the same browser needs to encounter an error when loading a page using HTTPS from the same host, not even from the host's subdomain
		&ndash; that's because <code>include_subdomains: true</code> <a href="https://www.w3.org/TR/network-error-logging/#privacy-considerations">doesn't apply</a> for the <em>connection</em> phase of the request for privacy reasons,
		and the <em>connection</em> phase is exactly where secure connection establishment errors <a href="https://www.w3.org/TR/network-error-logging/#secure-connection-establishment-errors">occur</a>. But you can&hellip;
	</p>
	<h3>Simulate a TLS error with Fiddler</h3>
	<p>Fiddler is a great HTTP debugging proxy originally written by <a href="https://twitter.com/ericlaw">Eric Lawrence</a> and you can use it to inspect HTTP and HTTPS traffic (and much more). We'll use it to generate a TLS error in your browser:</p>
	<ol>
		<li>Get <a href="https://www.telerik.com/fiddler">Fiddler</a></li>
		<li>Load this page to cache the NEL policy &ndash; somehow I think you've already done this step</li>
		<li>
			<a href="https://docs.telerik.com/fiddler/Configure-Fiddler/Tasks/DecryptHTTPS">Configure Fiddler to decrypt HTTPS traffic</a>
			but <strong>DO NOT</strong> <a href="https://docs.telerik.com/fiddler/Configure-Fiddler/Tasks/TrustFiddlerRootCert">trust the Fiddler Root Certificate</a>
		</li>
		<li>Load this page again and this is what's going to happen:
			<ul>
				<li>Your browser will send a request to the Fiddler proxy</li>
				<li>The proxy will resend it to the server</li>
				<li>The server will respond to Fiddler, encrypting the traffic with a valid certificate</li>
				<li>Fiddler will validate the certificate produced by the server and decrypt the traffic</li>
				<li>Fiddler will re-encrypt the traffic with it's own Root Certificate</li>
				<li>But your browser doesn't trust the Root Certificate and will show an <em>Invalid Certification Authority</em> error or similar</li>
				<li>Your browser will also generate the NEL report</li>
			</ul>
		</li>
		<li>Exit Fiddler now (or stop capturing traffic using <em>F12</em>) before your browser will try to actually send the generated report</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>You should see a <em>network-error</em> report with <code>"type": "tls.cert.authority_invalid"</code>, <code>"phase": "connection"</code></li>
	</ol>

	<p>See also Chrome's <a href="https://source.chromium.org/chromium/chromium/src/+/master:net/network_error_logging/network_error_logging_service.cc?q=kErrorTypes">list of all NEL types</a>.</p>
</div>
</body>
