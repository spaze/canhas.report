<?php
declare(strict_types = 1);

$expectCtHeader = 'Expect-CT: max-age=' . \Can\Has\maxAge() . ', enforce, report-uri="' . \Can\Has\reportUrl('ct/enforce') . '"';
header($expectCtHeader);

echo \Can\Has\pageHead('Expect-CT');
?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Expect-CT reports</h1>
	<p><em>
		Get a report when a browser loads your site with a TLS certificate that doesn't meet the requirements of the browser's Certificate Transparency (CT) policy.
		For example Google Chrome requires all publicly-trusted TLS certificates issued after April 30, 2018 to be "CT Qualified" in order to be recognized as valid.
		Being "CT-Qualified" essentially means that all such certificates has to be logged in two or more <a href="https://www.certificate-transparency.org/known-logs">known Certificate Transparency logs</a>
		which you can search with tools like <a href="https://crt.sh/">crt.sh</a>.
		With <code>Expect-CT</code> header, you can also enforce the requirement for certificates issued earlier (or issued later with a "valid from" set before the date).
		Check <a href="https://github.com/chromium/ct-policy/blob/master/ct_policy.md">Chrome's CT policy</a> to see what does it mean for the certificate to be "CT qualified".
		Apple has a <a href="https://support.apple.com/en-us/HT205280">similar CT policy</a>.
	</em></p>
	<h2>The <code>Expect-CT</code> response header:</h2>
	<pre><code class="json"><?= htmlspecialchars($expectCtHeader); ?></code></pre>
	<ul>
		<li><code>max-age</code>: for how many seconds should the browser remember to send violation reports, or enforce the policy</li>
		<li>
			<code>enforce</code>: optional, if present, the browser should refuse future connections that violate the CT policy, for <code>max-age</code> seconds after the reception of the <code>Expect-CT</code> header
			&ndash; using <code>enforce</code> doesn't make sense with <code>max-age: 0</code>, also keep in mind some browsers have their own CT requirements that cannot be disabled by simply omitting <code>enforce</code> or setting a short <code>max-age</code>
		</li>
		<li><code>report-uri</code>: where to send policy violation reports to, must use HTTPS</li>
	</ul>

	<h2>Test Expect-CT reporting</h2>
	<p>It would be quite a feat to get a certificate that would violate for example Chrome's CT policy so there's no "click here to generate the report" button here. Luckily, Chrome offers to send a test Expect-CT report:</p>
	<ol>
		<?php if (\Can\Has\reportToReportUri()) { ?>
			<li>While in Report URI, add <code>expect-ct-report.test</code> to <em>Global Report Filters</em> &gt; <em>Sites to collect reports for</em> in <a href="https://report-uri.com/account/filters/"><em>Filters</em></a></li>
		<?php } ?>
		<li>Go to Chrome's Domain Security Policy debug page: <a href="chrome://net-internals/#hsts">chrome://net-internals/#hsts</a> (you need to copy & paste the link)</li>
		<li>Scroll down to <em>Send test Expect-CT report</em></li>
		<li>Enter your custom reporting endpoint address <a href="<?= htmlspecialchars(\Can\Has\reportUrl('ct/enforce')); ?>"><?= htmlspecialchars(\Can\Has\reportUrl('ct/enforce')); ?></a> (copy & paste) to the <em>Report URI</em> field</li>

		<?php if (\Can\Has\reportToReportUri()) { ?>
			<li>Hit <em>Send</em> and you should see that the <em>Test report failed</em> but don't worry, that's ok &ndash; Report URI returns HTTP 201, not 200, and that's treated as failure here</li>
		<?php } else { ?>
			<li>Hit <em>Send</em> and you should see that the <em>Test report succeeded</em></li>
		<?php } ?>
		<li>Check your <a href="<?= htmlspecialchars(\Can\Has\reportViewer()); ?>">reports</a></li>
		<li>You'll find one <code>expect-ct-report</code> test report for <code>expect-ct-report.test</code> host, no <code>scts</code> and empty certificate chains</li>
	</ol>

	<h2>Example Expect-CT report</h2>
	<p>This is how the full report would look like:</p>
	<pre><code class="json"><?= \Can\Has\jsonReportHtml([
		'expect-ct-report' => [
			'port' => 443,
			'scts' => [
				[
					'serialized_sct' => '<Base64 Signed Certificate Timestamp data>',
					'source' => 'embedded',
					'status' => 'unknown',
					'version' => 1,
				],
				[
					'serialized_sct' => '<Base64 Signed Certificate Timestamp data>',
					'source' => 'embedded',
					'status' => 'unknown',
					'version' => 1,
				],
			],
			'hostname' => 'expect-ct-report.test',
			'date-time' => '2020-05-15T23:16:25.889Z',
			'served-certificate-chain' => [
				"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----\n",
				"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----\n",
			],
			'effective-expiration-date' => '2020-05-15T23:16:25.889Z',
			'validated-certificate-chain' => [
				"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----\n",
				"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----\n",
				"-----BEGIN CERTIFICATE-----\n<PEM certificate data>\n-----END CERTIFICATE-----\n",
			],
		],
	]); ?></code></pre>

</div>
<?= \Can\Has\footerHtml(); ?>
</body>
