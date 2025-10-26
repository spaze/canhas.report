<?php
declare(strict_types = 1);

$expectCtHeader = 'Expect-CT: max-age=' . \Can\Has\maxAge() . ', enforce, report-uri="' . \Can\Has\reportUrl('ct/enforce') . '"';
header($expectCtHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Expect-CT'); ?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Expect-CT reports</h1>
	<p><em>
		You could get a report when a browser loaded your site with a TLS certificate that didn't meet the requirements of the browser's Certificate Transparency (CT) policy.
		For example Google Chrome requires all publicly-trusted TLS certificates issued after April 30, 2018 to be "CT Qualified" in order to be recognized as valid.
		Nowadays, it means that Chrome requires CT on all public sites, so Expect-CT could be used only as a tool to detect misconfigurations.
		But CT certificate configuration is almost always done by certification authorities, virtually never by the site owners, so usefulness of Expect-CT as a debugging tool is also very limited.
		In October 2022, Chrome <a href="https://chromestatus.com/feature/6244547273687040">removed Expect-CT in version 107</a>. Chrome was also the only browser that had implemented the <code>Expect-CT</code> support.
	</em></p>
	<div class="not-supported">🍌 Your browser doesn't support Expect-CT, no reports will be sent</div>
	<h2>The <code>Expect-CT</code> response header:</h2>
	<pre><code><?= \Can\Has\highlight($expectCtHeader); ?></code></pre>
	<ul>
		<li><code>max-age</code>: for how many seconds should the browser remember to send violation reports, or enforce the policy</li>
		<li>
			<code>enforce</code>: optional, if present, the browser should refuse future connections that violate the CT policy, for <code>max-age</code> seconds after the reception of the <code>Expect-CT</code> header
			&ndash; using <code>enforce</code> doesn't make sense with <code>max-age: 0</code>, also keep in mind some browsers have their own CT requirements that cannot be disabled by simply omitting <code>enforce</code> or setting a short <code>max-age</code>
		</li>
		<li><code>report-uri</code>: where to send policy violation reports, must use HTTPS</li>
	</ul>

	<h2>Test Expect-CT reporting</h2>
	<p>It would be quite a feat to get a certificate that would violate for example Chrome's CT policy so there's no "click here to generate the report" button here. Until the feature was removed, Chrome offered to send a test Expect-CT report:</p>
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
	<p>This is how the full report looked like:</p>
	<pre><code><?= \Can\Has\jsonReportHtml([
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

	<?= \Can\Has\specsHtml('expect-ct'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
