<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$reportTo = [
	'group' => 'default',
	'max_age' => 60,
	'endpoints' => [
		[
			'url' => \Can\Has\reportUrl(),
		]
	],
	'include_subdomains' => true,
];
header('Report-To: ' . json_encode($reportTo, JSON_UNESCAPED_SLASHES));
echo \Can\Has\pageHead('Crash');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Crash reports with <code>report-to</code></h1>
	<p><em>Sending reports about browser or tab (where tab means one of the browser processes) crashes with Reporting API using the <code>Report-To</code> header (and only the <code>Report-To</code> header, no other header required), asynchronously.</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>
	<h2>The Report-To header:</h2>
	<pre><code class="json"><?= htmlspecialchars(\Can\Has\reportToHeader()); ?></code></pre>
	<ul>
		<li><code>group</code>: the name of the group (can be used in a CSP header in the <code>report-to</code> directive, for example)</li>
		<li><code>max_age</code>: how long the browser should use the endpoint and report errors to it</li>
		<li>
			<code>endpoints</code>: reporting endpoint configuration, can specify multiple endpoints but reports will be sent to just one of them
			<ul>
				<li><code>url</code>: where to send reports to, must be <code>https://</code>, otherwise the endpoint will be ignored</li>
			</ul>
		</li>
	</ul>

	<h2>Try crashing your tab</h2>
	<p>
		You can simulate a crash by copying and pasting the following link into this tab's address bar: <a href="chrome://crash/">chrome://crash/</a> (in Chrome) &ndash; it's not clickable for a good reason 😈
	</p>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml(); ?></li>
		<li>&hellip;if the tab crashes in <code>max_age</code> seconds after receiving the <code>Report-To</code> header, <a href="crash.php">reload</a> the page first to make sure it does (you'll want to use bigger <code>max_age</code> in your real header)</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>reports can contain an optional <code>reason</code>, e.g. <code>oom</code> (Out-of-Memory), <code>unresponsive</code> (killed due to being unresponsive)</li>
	</ul>
</div>
</body>
