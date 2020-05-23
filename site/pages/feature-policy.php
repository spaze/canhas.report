<?php
declare(strict_types = 1);

$reportToHeader = \Can\Has\reportToHeader();
$featurePolicyHeader = "Feature-Policy: geolocation 'none'; fullscreen 'self' https://www.michalspacek.cz";
header($reportToHeader);
header($featurePolicyHeader);

echo \Can\Has\pageHead('Feature Policy');
?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Feature Policy reports</h1>
	<p><em>
		Feature Policy allows web developers to selectively enable, disable, and modify the behavior of certain APIs and web features in the browser.
		The policies control what the browser can do and are inherited by all iframes on the page that has set the policy.
		That means for example that no iframe embedded in your page can go fullscreen, unless explicitly enabled, if your page has disallowed going fullscreen.
		Right now, only first-party reports will be sent, no reports for violations that happened in embedded iframes.
		The <code>Feature-Policy</code> header is similar to Content Security Policy header.
	</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>
	<div class="feature-policy not-supported hidden">🍌 Your browser doesn't support Feature Policy, no reports will be sent</div>
	<script>
		if (typeof document.featurePolicy === 'undefined') {
			const list = document.getElementsByClassName('feature-policy not-supported');
			for (let element of list) {
				element.classList.remove('hidden');
			}
		}
	</script>

	<h2>The <code>Feature-Policy</code> response header:</h2>
	<pre><code class="json"><?= htmlspecialchars($featurePolicyHeader); ?></code></pre>
	<ul>
		<li>
			<code>geolocation</code>: which origins can get the current location of the user's device
			<ul>
				<li><code>'none'</code>: no sites, not even iframes can query the location</li>
			</ul>
		</li>
		<li>
			<code>fullscreen</code>: which origins can go fullscreen
			<ul>
				<li><code>'self'</code>: current origin (scheme + host + port)</li>
				<li><code>https://www.michalspacek.cz</code>: or anything embedded in an iframe loaded from my site but not an embedded YouTube video for example</li>
			</ul>
		</li>
	</ul>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'the Feature Policy reports will always be sent to the group named <code>default</code>'); ?>

	<h2>Try getting the current location of the device</h2>
	<button id="geolocation" class="blocked">Get current geolocation</button>
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
		<li><span class="blocked">Blocked</span> by the current policy <code>geolocation 'none'</code></li>
		<li><?= \Can\Has\willTriggerReportToHtml('no violation'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>


	<h2>Embedded frame cannot go fullscreen</h2>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<iframe src="https://www.youtube-nocookie.com/embed/twqSIvSPQW0" frameborder="0"></iframe>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li>Fullscreen <span class="blocked">blocked</span> by the current <code>fullscreen</code> policy</li>
	</ul>

	<p>Fullscreen and other <em>features</em> can be allowed on a per iframe basis with <code>allow</code> attribute:</p>
	<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
	<iframe src="https://www.youtube-nocookie.com/embed/twqSIvSPQW0" frameborder="0" allow="fullscreen"></iframe>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>

	<h2>List of all features supported by your browser</h2>
	<ul id="features">
	</ul>
	<script>
		const features = document.getElementById('features');
		if (typeof document.featurePolicy === 'undefined') {
			const li = document.createElement('li');
			li.innerText = 'none'
			features.appendChild(li);
		} else {
			document.featurePolicy.features().forEach(function (feature) {
				const code = document.createElement('code')
				code.innerText = feature;
				const li = document.createElement('li');
				li.appendChild(code)
				features.appendChild(li);
			})
			const li = document.createElement('li');
			li.innerText = '… and counting';
			features.appendChild(li);
		}
	</script>

</div>
<?= \Can\Has\footerHtml(); ?>
</body>
