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
echo \Can\Has\pageHead('Deprecation');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>Deprecation reports</h1>
	<p><em>
		Some browser features, functions, or APIs are considered <em>deprecated</em>, no longer recommended, and while they still work, you shouldn't be using them.
		Deprecation reporting will send you a report if your code uses such deprecated feature, all you need to send is a <code>Report-To</code> response header
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

	<h2>Use a deprecated feature</h2>
	<p>
		Synchronous <code>XMLHttpRequest</code> outside of workers is in the process of <a href="https://xhr.spec.whatwg.org/#sync-warning">being removed</a> as it has detrimental effects to the user's experience and browsers have deprecated such usage.
		Developers must not pass <code>false</code> for the <code>async</code> argument of the <a href="https://xhr.spec.whatwg.org/#the-open()-method"><code>open()</code></a> method but you're a professional driver and this is a closed circuit, so&hellip;
	</p>

	<button id="xhr" class="blocked">Make a synchronous <code>XMLHttpRequest</code></button> by calling <code>new XMLHttpRequest().open('GET', 'foo', false)</code>
	<script>
	document.getElementById('xhr').onclick = function() {
		new XMLHttpRequest().open('GET', 'foo', false);
		alert('XMLHttpRequest.open(async = false) called');
	}
	</script>
	<ul>
		<li><?= \Can\Has\willTriggerReportToHtml(); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>
	<p>See Chrome's <a href="https://source.chromium.org/chromium/chromium/src/+/master:third_party/blink/renderer/core/frame/deprecation.cc?q=GetDeprecationInfo">source code</a> for more deprecated and invalid features.</p>
</div>
</body>
