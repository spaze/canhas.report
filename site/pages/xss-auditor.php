<?php
declare(strict_types = 1);

$xxpHeader = 'X-XSS-Protection: 1; report=' . \Can\Has\reportUrlCanHas();
header($xxpHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('XSS Auditor'); ?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>XSS Auditor reports</h1>
	<p><em>
		Designed to help stop <em>Reflected Cross-Site Scripting</em> (XSS) attacks but often exploited to extract information from pages.
		Introduced in Internet Explorer 8 back in 2009 as <em>XSS Filter</em> and in Chrome 4 in 2010, controlled by the <code>X-XSS-Protection</code> HTTP header.
		The feature was completely <a href="https://www.chromestatus.com/feature/5021976655560704">removed in Chrome 78</a>
		and in <a href="https://blogs.windows.com/windowsexperience/2018/07/25/announcing-windows-10-insider-preview-build-17723-and-build-18204/">Microsoft Edge in 2018</a>.
		Apple removed XSS Auditor <a href="https://webkit.org/blog/12445/new-webkit-features-in-safari-15-4/#security">in Safari 15.4</a>.
	</em></p>
	<div class="not-supported">🍌 Your browser doesn't support XSS Auditor, no reports will be sent</div>
	<h2>The <code>X-XSS-Protection</code> response header:</h2>
	<pre><code><?= htmlspecialchars($xxpHeader); ?></code></pre>
	<ul>
		<li><code>1</code>: enable the auditor</li>
		<li><code>report</code>: where to send the reports</li>
	</ul>

	<p>
		There's some JavaScript on this page, and hitting the button below will send the same JavaScript in the request.
		Now, browser sees a request with some code, then the same code comes back in the response, so this <em>must</em> be the Reflected XSS attack!
		The browser has no idea the code is always there and it's not in fact a Reflected XSS attack but will block it anyway.
	</p>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<script>console.log('Send this to the page I dare you, I double dare you!');</script>
	<form action="?auditor=triggered" method="post">
		<input type="hidden" name="some" value="input">
		<input type="hidden" name="trigger" value="<?= htmlspecialchars("<script>console.log('Send this to the page I dare you, I double dare you!');</script>"); ?>">
		<button disabled class="blocked disabled">Trigger the XSS Auditor</button>
		<span class="not-supported">🍌 Not supported in your browser</span>
		<small>but you can try <button>anyway</button></small>
	</form>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li>Would be <span class="blocked">blocked</span> if supported by your browser, the JavaScript wouldn't run</li>
		<li>Would trigger a report in supported browsers</li>
		<li>Check your <a href="<?= htmlspecialchars(\Can\Has\reportOrigin()); ?>/">reports</a></li>
	</ul>

	<h2>Example XSS Auditor report</h2>
	<p>This is how the report looked like:</p>
	<pre><code><?= \Can\Has\jsonReportHtml([
		'xss-report' => [
			'request-url' => '<URL>',
			'request-body' => '<post data, if any>',
		],
	]); ?></code></pre>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
