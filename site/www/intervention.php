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
echo \Can\Has\pageHead('Intervention');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Intervention reports</h1>
	<p><em>
		Intervention reports indicate that a browser has decided to not do what the server asked it to do for security, performance or user annoyance reasons.
		The browser may for example block phone vibration unless the user has already interacted with the page somehow
	</em></p>
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

	<h2>Use an "intervened" feature</h2>
	<p>
		The mousewheel (in Chromium-based browsers) and touch (also in Firefox) event listeners that are registered on document level targets  (e.g. <code>window.document</code>)
		will be treated as <a href="https://developers.google.com/web/updates/2016/06/passive-event-listeners">passive</a> (it has something to do with custom scrolling)
		if not specified as otherwise and calling <code>preventDefault()</code> inside such listeners will be ignored
	</p>
	<button id="wheel" class="blocked">Add <code>wheel</code> and <code>touchstart</code> handlers</button> and then use your mousewheel or your finger to scroll
	<script>
	document.getElementById('wheel').onclick = function() {
		let sent = 0;
		const handler = function (event) {
			event.preventDefault();
			console.log('Whee');
			sent++;
			if (sent === 3) {
				window.removeEventListener('wheel', handler);
				window.removeEventListener('touchstart', handler);
			}
		};
		window.addEventListener('wheel', handler);
		window.addEventListener('touchstart', handler);
		alert('Wheel and touchstart handlers added, now scroll');
	}
	</script>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('intervention'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>only three reports per page load will be sent in this demo to not spam you with identical reports, then the handlers will be automatically removed</li>
	</ul>
	<p>
		See Chrome's incomplete <a href="https://www.chromestatus.com/features#intervention">list of interventions</a>
		(the above-mentioned <a href="https://www.chromestatus.com/feature/5644273861001216">phone vibrate intervention</a> is not included in this list for some reason).
	</p>
</div>
</body>
