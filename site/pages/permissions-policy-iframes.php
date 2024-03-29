<?php
declare(strict_types = 1);

$reportToHeader = \Can\Has\reportToHeader();
$permissionsPolicyHeader = 'Permissions-Policy: fullscreen=(self "https://exploited.cz")';
header($reportToHeader);
header($permissionsPolicyHeader);
$iframeUrl = 'https://exploited.cz/frames/fullscreen/fullscreen.html';
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Permissions Policy in iframes'); ?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Permissions Policy <code>&lt;iframe&gt;</code> restrictions</h1>
	<p><em>
		Permissions Policy allows web developers to selectively enable, disable, and modify the behavior of certain APIs and web features in the browser,
			and query the state (allowed or denied) in the current document for a given feature.
		The policies control what the browser can do and are inherited by all iframes on the page that has set the policy.
		That means for example that no iframe embedded in your page can go fullscreen, unless explicitly enabled, if your page has disallowed going fullscreen.
	</em></p>
	<?= \Can\Has\permissionsPolicyNotSupportedHtml('but <code>fullscreen</code> on <code>&lt;iframe&gt;</code> is partially supported in Firefox'); ?>
	<?= \Can\Has\reportingApiNotSupportedHtml('but no reports would be sent from this page anyway'); ?>
	<h2>The <code>Permissions-Policy</code> response header:</h2>
	<pre><code class="json"><?= \Can\Has\highlight($permissionsPolicyHeader); ?></code></pre>
	<ul>
		<li>
			<code>fullscreen</code>: which origins can go fullscreen
			<ul>
				<li><code>self</code>: current origin (scheme + host + port)</li>
				<li><code>"https://exploited.cz"</code>: or anything embedded in an iframe loaded from my other site but not an embedded YouTube video for example, must be quoted</li>
			</ul>
		</li>
	</ul>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'the Permissions Policy reports will always be sent to the group named <code>default</code>'); ?>
	<p><em>Note: the <code>Report-To</code> header here is mostly useless as the following violations happen in 3<sup>rd</sup> party embedded iframes, and no reports are sent in such cases.</em></p>

	<h2>Embedded frame cannot go fullscreen</h2>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<iframe src="<?= htmlspecialchars($iframeUrl) ?>"></iframe>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li>Fullscreen <span class="blocked">blocked</span> by the current <code>fullscreen</code> policy</li>
		<li><strong>No report will be sent</strong>, because the violation happened in an embedded iframe, and only first-party reports are sent</li>
		<li>Violation will be visible in Developer Tools in the <em>Console</em> tab</li>
	</ul>

	<p>Fullscreen and other <em>features</em> can be allowed on a per-iframe basis with an <code>allow</code> attribute provided the <code>Permissions-Policy</code> header also contains the origin:</p>
	<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
	<iframe src="<?= htmlspecialchars($iframeUrl) ?>" allow="fullscreen"></iframe>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><span class="allowed">Allowed</span> because the <code>Permissions-Policy</code> header's <code>fullscreen</code> policy contains <code>https://exploited.cz</code></li>
		<li>… <strong>and</strong> the iframe's <code>allow</code> attribute contains <code>fullscreen</code></li>
		<li>Browsers with partial Permissions Policy support (or partial Feature Policy support) respect the <code>allow="fullscreen"</code> attribute and don't need (nor understand) the HTTP header</li>
	</ul>

	<?= \Can\Has\specsHtml('permissions-policy'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
