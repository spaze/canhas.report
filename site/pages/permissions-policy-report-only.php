<?php
declare(strict_types = 1);

$reportingEndpointsHeader = \Can\Has\reportingEndpointsHeader();
$permissionsPolicyHeader = 'Permissions-Policy-Report-Only: fullscreen=()';
header($reportingEndpointsHeader);
header($permissionsPolicyHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Permissions Policy Report-Only'); ?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Permissions Policy <em>Report-Only</em> reports</h1>
	<p><em>
		All Permission Policy features <a href="permissions-policy#supported-features">supported</a> by your browser will function as usual, without any restrictions.
		If the policy would be violated, a report will be sent (with <code>"disposition": "report"</code> rather than <code>"disposition": "enforce"</code>).
		This is useful if you want to add a new policy or change the existing one, to see what would break, if you enforced the policy with <code>Permissions-Policy</code> header.
	</em></p>
	<p><em>
		Permissions Policy allows web developers to selectively enable, disable, and modify the behavior of certain APIs and web features in the browser,
			and query the state (allowed or denied) in the current document for a given feature. See the <a href="permissions-policy">Permissions Policy</a> page for more details.
	</em></p>
	<?= \Can\Has\permissionsPolicyFirstPartyReportsHtml(); ?>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>
	<?= \Can\Has\permissionsPolicyNotSupportedHtml() ?>
	<h2>The <code>Permissions-Policy-Report-Only</code> response header:</h2>
	<pre><code class="json"><?= \Can\Has\highlight($permissionsPolicyHeader); ?></code></pre>
	<ul>
		<li>
			<code>fullscreen</code>: which origins can switch to full screen view
			<ul>
				<li><em>empty</em>: no sites, not even iframes can go full screen</li>
			</ul>
		</li>
	</ul>

	<?= \Can\Has\reportingEndpointsHeaderHtml($reportingEndpointsHeader, \Can\Has\permissionsPolicyEndpointNameDescriptionHtml()); ?>

	<h2>Go full screen</h2>
	<button id="fullscreen" class="allowed">Toggle full screen</button>
	<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
	<script>
		document.getElementById('fullscreen').onclick = function() {
			if (!document.fullscreenElement) {
				document.getElementsByTagName('html')[0].requestFullscreen()
					.catch(function (error) {
						alert(error.message);
					});
			} else {
				document.exitFullscreen()
					.catch(function (error) {
						alert(error.message);
					});
			}
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><span class="allowed">Allowed</span> even though the current policy contains <code>fullscreen=()</code></li>
		<li>Going full screen would be blocked if the policy was <em>enforced</em> and not <em>report-only</em></li>
		<li><?= \Can\Has\willTriggerReportToHtml('no violation'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<?= \Can\Has\specsHtml('permissions-policy', 'reporting-api'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
