<?php
declare(strict_types = 1);

echo \Can\Has\pageHead('Certification Authority Authorization (CAA)');
?>
<body>
<?= \Can\Has\headerHtml('Reporting Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index'); ?>

	<h1>Certification Authority Authorization (CAA) reports</h1>
	<p><em>
		Certification Authority Authorization (CAA) allows a domain name holder to specify one or more Certification Authorities (CAs) authorized to issue certificates for that domain.
		CAA is an optional DNS record but if present, CAs must check if their domain name is in the <code>issue</code> or <code>issuewild</code> (for wildcard certificates) properties.
	</em></p>

	<h2>Example CAA DNS record</h2>
	<pre><code>CAA 0 issue "letsencrypt.org"<br>CAA 0 issue "example.com"<br>CAA 0 iodef "mailto:security@example.net"</code></pre>
	<ul>
		<li><code>0</code>: "Critical Flag" intended to introduce new properties in the future, at the moment only <code>0</code> is allowed</li>
		<li>
			<code>issue</code>: which CAs (Let's Encrypt in this case and a hypothetical Example CA) can issue both regular and wildcard certificates for this domain
			&ndash; add <code>issuewild</code> if you want some other CA to issue wilcard certificates, in that case any existing <code>issue</code>s are ignored by the CA when processing a request for a wildcard certificate
		</li>
		<li>
			<code>iodef</code>: URL (<code>mailto:</code>, <code>http:</code>, <code>https:</code>) where reports of invalid certificate requests may be sent to, in <em>Incident Object Description Exchange Format</em>
		</li>
	</ul>
	<p>The <em>canhas.report</em> domain <a href="https://toolbox.googleapps.com/apps/dig/#CAA/canhas.report">has a similar DNS CAA record</a>.</p>

	<h2>Example report</h2>
	<p>
		I'm currently not aware of any certification authority sending CAA reports and the <em>Incident Object Description Exchange Format</em> is quite extensive to come up with an artificial example report.
		Let me know if you are a CA sending CAA reports or if you know about one.
	</p>

	<?= \Can\Has\specsHtml('caa'); ?>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
