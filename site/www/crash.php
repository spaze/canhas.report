<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$reportToHeader = \Can\Has\reportToHeader();
header($reportToHeader);

echo \Can\Has\pageHead('Crash');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Crash reports with <code>report-to</code></h1>
	<p><em>Sending reports about browser or tab (where tab means one of the browser processes) crashes with Reporting API using the <code>Report-To</code> header (and only the <code>Report-To</code> header, no other header required), asynchronously.</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'can be used in a CSP header in the <code>report-to</code> directive, for example'); ?>

	<h2>Try crashing your tab</h2>
	<p>
		You can simulate a crash by copying and pasting the following link into this tab's address bar: <a href="chrome://crash/">chrome://crash/</a> (in Chrome) &ndash; it's not clickable for a good reason 😈
	</p>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml(); ?></li>
		<li>&hellip;if the tab crashes in <code>max_age</code> seconds after receiving the <code>Report-To</code> header, <a href="crash.php">reload</a> the page first to make sure it does (you'll want to use bigger <code>max_age</code> in your real header)</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>Reports can contain an optional <code>reason</code>, e.g. <code>oom</code> (Out-of-Memory, try with <a href="chrome://memory-exhaust/">chrome://memory-exhaust/</a>), <code>unresponsive</code> (killed due to being unresponsive)</li>
	</ul>

	<?= \Can\Has\footerHtml(); ?>
</div>
</body>
