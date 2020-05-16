<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

echo \Can\Has\pageHead('HPKP');
?>
<body>
<div>
	<?= \Can\Has\bookmarks('index', 'reports'); ?>

	<h1>HTTP-based Public Key Pinning (HPKP)</h1>
	<p><em>
		<em>HTTP-based Public Key Pinning (HPKP)</em> allowed websites to send an HTTP header that tells the browser to "pin" one or more of the public keys and then to reject responses
		that came with a different public key, protecting against spoofed but still valid TLS certificates. It was a massive footgun, creating risks of denial of service, and as such was
		<a href="https://www.chromestatus.com/feature/5903385005916160">removed in Chrome 72</a> and disabled by default <a href="https://groups.google.com/d/msg/mozilla.dev.platform/AyMlrNHYepE/B5bgjjsiBwAJ">in Firefox 72</a>.
		Chrome was the only browser that supported reporting via the <code>report-uri="&lt;url&gt;"</code> field of the <code>Public-Key-Pins</code> or <code>Public-Key-Pins-Report-Only</code> headers.
	</em></p>
</div>
</body>
