<?php
declare(strict_types = 1);

$reportToHeader = \Can\Has\reportToHeader();
header($reportToHeader);

echo \Can\Has\pageHead('Deprecation');
?>
<body>
<?= \Can\Has\headerHtml('Reporting API Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Deprecation reports</h1>
	<p><em>
		Some browser features, functions, or APIs are considered <em>deprecated</em>, no longer recommended, and while they still work, you shouldn't be using them.
		Deprecation reporting will send you a report if your code uses such deprecated feature, all you need to send is a <code>Report-To</code> response header.
	</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'can be used in a CSP header in the <code>report-to</code> directive, for example'); ?>

	<h2>Use a deprecated feature</h2>
	<p>
		Synchronous <code>XMLHttpRequest</code> outside of workers is in the process of <a href="https://xhr.spec.whatwg.org/#sync-warning">being removed</a> as it has detrimental effects to the user's experience and browsers have deprecated such usage.
		Developers must not pass <code>false</code> for the <code>async</code> argument of the <a href="https://xhr.spec.whatwg.org/#the-open()-method"><code>open()</code></a> method but you're a professional driver and this is a closed circuit, so&hellip;
	</p>
	<button id="xhr" class="blocked">Make a synchronous <code>XMLHttpRequest</code></button> by calling <code>new XMLHttpRequest().open('GET', 'foo', false)</code>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<script>
		document.getElementById('xhr').onclick = function() {
			new XMLHttpRequest().open('GET', 'foo', false);
			alert('XMLHttpRequest.open(async = false) called');
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('deprecation'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<h2>Use an invalid feature</h2>
	<p>
		Some features are considered invalid, and won't work at all, for example using <code>&lt;source src&gt;</code> HTML element with a <code>&lt;picture&gt;</code> parent, instead of <code>&lt;source srcset&gt;</code>.
		But you will still get a report if you use them.
	</p>
	<button id="src" class="blocked">Create a <code>&lt;picture&gt;</code> with <code>&lt;source src&gt;</code></button> instead of <code>&lt;source srcset&gt;</code>
	<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
	<script>
		document.getElementById('src').onclick = function() {
			const source = document.createElement('source'), img = document.createElement('img');
			source.src = 'data:image/webp;base64,UklGRhIAAABXRUJQVlA4TAYAAAAvQWxvAGs=';
			img.src = 'data:image/gif;base64,R0lGODlhAQABAAAAADs=';
			document.createElement('picture').append(source, img);
			alert('<picture>\n  <source src> ← this is invalid\n  <img src>\n</picture>\nhas been created');
		}
	</script>
	<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml('invalid feature'); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>
	<p>See Chrome's <a href="https://source.chromium.org/chromium/chromium/src/+/master:third_party/blink/renderer/core/frame/deprecation.cc?q=GetDeprecationInfo">source code</a> for more deprecated and invalid features.</p>

	<?= \Can\Has\specsHtml('reporting-api'); ?>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
