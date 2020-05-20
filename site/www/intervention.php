<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$reportToHeader = \Can\Has\reportToHeader();
header($reportToHeader);

echo \Can\Has\pageHead('Intervention');
?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Intervention reports</h1>
	<p><em>
		Intervention reports indicate that a browser has decided to not do what the server asked it to do for security, performance or user annoyance reasons.
		The browser may for example block phone vibration unless the user has already interacted with the page somehow.
	</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'can be used in a CSP header in the <code>report-to</code> directive, for example'); ?>

	<h2>Use an "intervened" feature</h2>
	<p>
		The mousewheel (in Chromium-based browsers) and touch (also in Firefox) event listeners that are registered on document level targets  (e.g. <code>window.document</code> or <code>window</code>)
		will be treated as <a href="https://developers.google.com/web/updates/2016/06/passive-event-listeners">passive</a> (it has something to do with custom scrolling)
		if not specified as otherwise and calling <code>preventDefault()</code> inside such listeners will be ignored
	</p>
	<button id="wheel" class="blocked">Add <code>wheel</code> and <code>touchstart</code> handlers</button> and then use your mousewheel or your finger to scroll
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
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
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('intervention'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>Only three reports per page load will be sent in this demo to not spam you with identical reports, then the handlers will be automatically removed</li>
	</ul>
	<p>
		See Chrome's incomplete <a href="https://www.chromestatus.com/features#intervention">list of interventions</a>
		(the above-mentioned <a href="https://www.chromestatus.com/feature/5644273861001216">phone vibrate intervention</a> is not included in this list for some reason).
	</p>

	<?= \Can\Has\footerHtml(); ?>
</div>
</body>
