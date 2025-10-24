<?php
declare(strict_types = 1);

$reportingEndpointsHeader = \Can\Has\reportingEndpointsHeader();
$permissionsPolicyHeader = 'Permissions-Policy: geolocation=(), fullscreen=(), camera=(self "https://www.michalspacek.com"), midi=*';
header($reportingEndpointsHeader);
header($permissionsPolicyHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Permissions Policy'); ?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Permissions Policy reports</h1>
	<p><em>
		Permissions Policy allows web developers to selectively enable, disable, and modify the behavior of certain APIs and web features in the browser,
			and query the state (allowed or denied) in the current document for a given feature.
		The policies control what the browser can do and are inherited by all iframes on the page that has set the policy.
		That means for example that no iframe embedded in your page can go fullscreen, unless explicitly enabled, if your page has disallowed going fullscreen.
	</em></p>
	<p><em>
		The <code>Permissions-Policy</code> header is similar to Content Security Policy header, although the syntax is different
			as the <code>Permissions-Policy</code> header is defined as a <a href="https://datatracker.ietf.org/doc/html/rfc8941">Structured Header</a>.
		Permissions Policy, shipped in Chrome 88, was previously known as Feature Policy and was available in Chrome since 2016. Both Permissions Policy and Feature Policy share the same ideas
			but the <code>Feature-Policy</code> header used a <a href="https://github.com/w3c/webappsec-permissions-policy/blob/main/permissions-policy-explainer.md#new-header">different format</a>
			and treated iframe <code>allow</code> attribute <a href="https://github.com/w3c/webappsec-permissions-policy/blob/main/permissions-policy-explainer.md#header-and-allow-attribute-combine-differently">differently</a>.
		The migration is not fully finished yet and the old name still has to be used in scripts.
	</em></p>
	<?= \Can\Has\permissionsPolicyFirstPartyReportsHtml(); ?>
	<?= \Can\Has\reportingApiNotSupportedHtml(); ?>
	<?= \Can\Has\permissionsPolicyNotSupportedHtml(); ?>
	<h2>The <code>Permissions-Policy</code> response header:</h2>
	<pre><code class="json"><?= \Can\Has\highlight($permissionsPolicyHeader); ?></code></pre>
	<ul>
		<li>
			<code>geolocation</code>: which origins can get the current location of the user's device
			<ul>
				<li><em>empty</em>: no sites, not even this one, not even iframes can query the location</li>
			</ul>
		</li>
		<li>
			<code>fullscreen</code>: which origins can go fullscreen
			<ul>
				<li><em>empty</em>: content from no sites, not even from iframes (see <a href="permissions-policy-iframes">demo</a>), can switch to full screen</li>
			</ul>
		</li>
		<li>
			<code>camera</code>: which origins can use your camera, if you'll allow it (<em>listed only as a syntax example</em>)
			<ul>
				<li><code>self</code>: this very site</li>
				<li><code>"https://www.michalspacek.com"</code>: my other site, even when in an iframe; the value must be quoted</li>
			</ul>
		</li>
		<li>
			<code>midi</code>: which origins can use your <abbr title="Musical Instrument Digital Interface">MIDI</abbr> devices through Web MIDI API (<em>listed only as a syntax example</em>)
			<ul>
				<li><code>*</code>: all sites <em>because why not</em></li>
			</ul>
		</li>
	</ul>

	<?= \Can\Has\reportingEndpointsHeaderHtml($reportingEndpointsHeader, 'the Permissions Policy reports will always be sent to the group named <code>default</code>'); ?>

	<h2>Try getting the current location of the device</h2>
	<button id="geolocation" class="blocked">Get current geolocation</button>
	<span class="permissions-policy not-supported hidden">🍌 Geolocation will work, your browser doesn't support Permissions Policy</span>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<script>
		document.getElementById('geolocation').onclick = function() {
			navigator.geolocation.getCurrentPosition(
				function (position) {
					alert('Your current position is:\nLatitude: ' + position.coords.latitude + '\nLongitude: ' + position.coords.longitude + '\n± ' + position.coords.accuracy + ' meters');
				},
				function (error) {
					alert(error.message);
				}
			);
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><span class="blocked">Blocked</span> by the current policy <code>geolocation=()</code>, the feature is disabled in all contexts everywhere, in all frames</li>
		<li><?= \Can\Has\willTriggerReportToHtml('no violation'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<h2>Try going full screen</h2>
	<button id="fullscreen" class="blocked">Go full screen</button>
	<span class="permissions-policy not-supported hidden">🍌 It will work, your browser doesn't support Permissions Policy</span>
	<span id="fullscreen-message" class="blocked"></span>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<script>
		document.getElementById('fullscreen').onclick = function() {
			const message = document.getElementById('fullscreen-message');
			document.getElementsByTagName('html')[0].requestFullscreen()
				.catch(error => { message.innerText = error.message; });
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><span class="blocked">Blocked</span> by the current policy <code>fullscreen=()</code>, the feature is not allowed even in iframes</li>
		<li><?= \Can\Has\willTriggerReportToHtml('no violation'); ?></li>
		<li>This is a first-party report, the violation happened on this page, not in an embedded iframe</li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>


	<h2 id="supported-features">List of all features supported by your browser</h2>
	<span>The list as returned by JavaScript after calling <code>document.featurePolicy.features()</code> (yes, it is still called <code>featurePolicy</code> here):</span>
	<ul id="features">
	</ul>
	<script>
		const features = document.getElementById('features');
		const policy = document.permissionsPolicy ?? document.featurePolicy;
		if (typeof policy === 'undefined') {
			const li = document.createElement('li');
			li.innerText = 'none'
			features.appendChild(li);
		} else {
			policy.features().sort().forEach(function (feature) {
				const code = document.createElement('code')
				code.innerText = feature;
				const li = document.createElement('li');
				li.appendChild(code)
				features.appendChild(li);
			})
			const counting = document.createElement('a');
			counting.href = 'https://github.com/w3c/webappsec-permissions-policy/blob/main/features.md';
			counting.innerText = 'counting';
			const li = document.createElement('li');
			li.innerText = '… and ';
			li.appendChild(counting);
			features.appendChild(li);
		}
	</script>

	<?= \Can\Has\specsHtml('permissions-policy', 'reporting-api'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
