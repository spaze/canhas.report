<?php
declare(strict_types = 1);
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1><?= $pageHeaderHtml; ?></h1>
	<p><em><?= $pageDescriptionHtml; ?></em></p>
	<?= $includeReportingApiNotSupportedWarning ? \Can\Has\reportingApiNotSupportedHtml() : ''; ?>
	<h2><?= htmlspecialchars($cspHeaderDescription); ?>:</h2>
	<pre><code class="csp"><?= htmlspecialchars($cspHeader); ?></code></pre>
	<ul>
		<li><code>default-src</code>: what's allowed by default, includes images, fonts, JavaScript <a href="https://www.w3.org/TR/CSP3/#directive-default-src">and more</a></li>
		<li>
			<code>img-src</code>: where to load images from, overrides <code>default-src</code> for images
			<ul>
				<li><code>'self'</code> means current URL's origin (scheme + host + port)</li>
			</ul>
		</li>
		<li>
			<code>script-src</code>: what JavaScript is allowed to be executed
			<ul>
				<li><code>'nonce-<?= htmlspecialchars($nonce); ?>'</code> means <code>script</code> elements with <code>nonce="<?= htmlspecialchars($nonce); ?>"</code> attribute</li>
				<li><code>'report-sample'</code> instructs the browser to include the first 40 characters of the blocked JavaScript in the report</li>
			</ul>
		</li>
		<li><code>style-src</code>: allowed CSS sources</li>
		<li><code><?= htmlspecialchars($reportDirective); ?></code>: <?= htmlspecialchars($reportDirectiveDescription); ?></li>
	</ul>

	<?= $additionalHeaderHtml; ?>

	<h2>Try it with images</h2>
	<img src="https://www.michalspacek.cz/i/images/photos/michalspacek-trademark-400x268.jpg" width="100" height="67" id="image" alt="Loaded image">
	<p>
		<button id="allowed" class="allowed">Click to load</button>
		another image from <em>https://www.michalspacek.cz</em> (<span class="allowed">allowed</span>)
		<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('allowed').onclick = function(e) {
				document.getElementById('image').src = 'https://www.michalspacek.cz/i/images/photos/michalspacek-webtop100-400x268.jpg';
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<p>
		Now simulate an attacker:<br>
		<button id="blocked" class="blocked">Click to load</button> an image from <em>https://example.com</em>
		<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('blocked').onclick = function(e) {
				document.getElementById('image').src = 'https://example.com/image.png';
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="blocked">blocked</span></li>
		<li><?= $willTriggerReportHtml; ?></li>
		<li><?= $checkReportsHtml; ?></li>
	</ul>

	<h2>&hellip; and with JavaScript</h2>
	<p>
		<button id="js" class="allowed">Click to <code>alert('hi')</code></button> (<span class="allowed">allowed</span>, the <code>script</code> tag contains <code>nonce="<?= htmlspecialchars($nonce); ?>"</code>)
		<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('js').onclick = function(e) {
				alert('hi');
			};
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<p>
		Simulate an attacker:<br>
		<button id="inject" class="blocked">Click to inject</button> blocked JS tag
		<?php \Can\Has\scriptSourceHtmlStart('blocked'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('inject').onclick = function() {
				const script = document.createElement('script');
				script.text = 'console.log("lo")';
				document.getElementById('inject').insertAdjacentElement('afterend', script);
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="blocked">blocked</span> because the injected JS tag created by <code>document.createElement()</code> doesn't have a <code>nonce</code></li>
		<li><?= $willTriggerReportHtml; ?></li>
		<li><?= $checkReportsHtml; ?></li>
		<li>see the inserted JS tag in <em>Developer tools</em>, right after <code>&lt;button id="inject"&gt;</code></li>
	</ul>
</div>
</body>
