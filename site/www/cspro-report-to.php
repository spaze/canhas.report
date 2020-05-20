<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$nonce = base64_encode(random_bytes(16));
$cspHeader = "Content-Security-Policy-Report-Only: default-src data: 'self' 'nonce-{$nonce}' 'report-sample'; report-to default";
$reportToHeader = \Can\Has\reportToHeader();
header($cspHeader);
header($reportToHeader);

echo \Can\Has\pageHead('CSPRO report-to');
?>
<body>
<?= \Can\Has\headerHtml('Browser Reporting Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>
	<h1>Content Security Policy <em>Report-Only</em> with <code>report-to</code></h1>
	<p><em>
		Loading images, executing JavaScript and everything else as usual but sending a Content Security Policy (CSP) violation report (with <code>"disposition": "report"</code> instead of <code>"disposition": "enforce"</code>) if something would go wrong.<br><br>
		CSP is a policy that lets the authors (or server administrators) of a web application inform the browser about the sources from which the application expects to load resources like images, scripts, styles, or even where to submit forms.
		This Report-Only mode works with both <code>report-uri</code> and <code>report-to</code> directives, and is usually used for policy upgrades &ndash; an app can send both <code>Content-Security-Policy</code> and <code>Content-Security-Policy-Report-Only</code> headers with different policies.
	</em></p>
	<?= \Can\Has\reportingApiNotSupportedHtml() ?>
	<h2>The CSPRO (CSP Report-Only) response header:</h2>
	<pre><code class="csp"><?= htmlspecialchars($cspHeader); ?></code></pre>
	<ul>
		<li>
			<code>default-src</code>: what's allowed by default, includes images, fonts, JavaScript <a href="https://www.w3.org/TR/CSP3/#directive-default-src">and more</a>
			<ul>
				<li><code>data:</code> used for the placeholder image below</li>
				<li><code>'self'</code> means current URL's origin (scheme + host + port)</li>
				<li><code>'nonce-<?= htmlspecialchars($nonce); ?>'</code> means <code>script</code> elements with <code>nonce="<?= htmlspecialchars($nonce); ?>"</code> attribute</li>
				<li>
					<code>'report-sample'</code> instructs the browser to include a violation sample, the first 40 characters
					(valid for CSS, JS only but included in <code>default-src</code> here to keep the header short)
				</li>
			</ul>
		</li>
		<li><code>report-to</code>: name of the group where to send violation reports to</li>
	</ul>

	<?= \Can\Has\reportToHeaderHtml($reportToHeader, 'the same as in the CSP header in the <code>report-to</code> directive'); ?>

	<h2>Try it with images</h2>
	<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABDAQMAAABQhTKZAAAABlBMVEX////MzMw46qqDAAAAI0lEQVR4AWP4f4D/D4xgGGAeg/0H5v8wYmB5gytcRsNlNFwAFna2DZiUiFYAAAAASUVORK5CYII=" id="image" width="100" height="67" alt="Loaded image">
	<p>
		<button id="allowed" class="allowed">Click to load</button>
		an image from <em>https://www.michalspacek.cz</em> (<span class="allowed">allowed</span>)
		<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('allowed').onclick = function(e) {
				document.getElementById('image').src = 'https://www.michalspacek.cz/i/images/photos/michalspacek-trademark-400x268.jpg';
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li><span class="allowed">Allowed</span> even though the image was loaded from <em>https://www.michalspacek.cz</em> and not from <em>this origin</em></li>
		<li><?= \Can\Has\willTriggerReportToHtml(); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
	</ul>

	<h2>&hellip; and with JavaScript</h2>
	<p>
		<button id="insert" class="allowed">Click to insert a text</button> <em id="here"></em>
		<?php \Can\Has\scriptSourceHtmlStart('allowed'); ?>
		<script nonce="<?= htmlspecialchars($nonce); ?>">
			document.getElementById('insert').onclick = function() {
				const script = document.createElement('script');
				script.text = 'document.getElementById("here").innerHTML = "by JavaScript with <code>document.getElementById(&apos;here&apos;).innerHTML</code>";';
				document.getElementById('insert').insertAdjacentElement('afterend', script);
				alert('Text inserted');
			}
		</script>
		<?php \Can\Has\scriptSourceHtmlEnd(); ?>
	</p>
	<ul>
		<li>
			<span class="allowed">Allowed</span> even though it's inserted by an inline JavaScript (the code between <code>&lt;script&gt;</code> and <code>&lt;/script&gt;</code>)
			and not loaded from <em>this origin</em> (<code>'self'</code> doesn't include inline JavaScript),
			and has no <code>nonce</code> attribute
		</li>
		<li><?= \Can\Has\willTriggerReportToHtml(); ?></li>
		<li><?= \Can\Has\checkReportsReportToHtml(); ?></li>
		<li>See the inserted JS tag in <em>Developer tools</em>, right after <code>&lt;button id="insert"&gt;</code></li>
	</ul>

	<?= \Can\Has\footerHtml(); ?>
</div>
</body>
