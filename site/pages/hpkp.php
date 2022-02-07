<?php
declare(strict_types = 1);

echo \Can\Has\pageHead('HPKP');
?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>HTTP-based Public Key Pinning (HPKP)</h1>
	<p><em>
		<em>HTTP-based Public Key Pinning (HPKP)</em> allowed websites to send an HTTP header that tells the browser to "pin" one or more of the public keys and then to reject responses
		that came with a different public key, protecting against spoofed but still valid TLS certificates. It was a massive footgun, creating risks of denial of service, and as such was
		<a href="https://www.chromestatus.com/feature/5903385005916160">removed in Chrome 72</a> and disabled by default <a href="https://groups.google.com/d/msg/mozilla.dev.platform/AyMlrNHYepE/B5bgjjsiBwAJ">in Firefox 72</a>.
		Chrome was the only browser that supported reporting via the <code>report-uri="&lt;url&gt;"</code> field of the <code>Public-Key-Pins</code> or <code>Public-Key-Pins-Report-Only</code> headers.
	</em></p>

	<h2>Example HPKP report</h2>
	<p>This is how the report looked like:</p>
	<pre><code><?= \Can\Has\jsonReportHtml([
		'date-time' => '2018-09-02T15:31:07.231Z',
		'effective-expiration-date' => '2018-10-02T15:31:02.188Z',
		'hostname' => 'www.example.com',
		'include-subdomains' => true,
		'known-pins' => [
			'pin-sha256="d6qzRu9zOECb90Uez27xWltNsj0e1Md7GkYYkVoZWmM="',
			'pin-sha256="E9CZ9INDbd+2eRQozYqqbQ2yXLVKB9+xcprMF+44U1g="',
		],
		'noted-hostname' => 'example.com',
		'port' => 443,
		'served-certificate-chain' => [
			"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----",
			"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----",
		],
		'validated-certificate-chain' => [
			"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----",
			"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----",
			"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----",
		],
	]); ?></code></pre>

</div>
<?= \Can\Has\footerHtml(); ?>
</body>
