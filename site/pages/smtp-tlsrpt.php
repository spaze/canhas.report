<?php
declare(strict_types = 1);

echo \Can\Has\pageHead('SMTP TLS Reporting (SMTP TLSRPT)');
?>
<body>
<?= \Can\Has\headerHtml('Reporting Demos'); ?>
<div>
	<?= \Can\Has\bookmarks('index'); ?>

	<h1>SMTP TLS Reporting (SMTP TLSRPT)</h1>
	<p><em>
		SMTP TLSRPT defines a mechanism for domains that send emails and that are compatible with MTA-STS or DANE to share success and failure statistics with recipient domains.
		SMTP MTA Strict Transport Security (MTA-STS) allows domains to declare their ability to receive Transport Layer Security (TLS) secure SMTP connections and potentially require them for message delivery,
		and DNS-Based Authentication of Named Entities (DANE) uses TLSA DNS records to associate a TLS server certificate with the domain name.
		The SMTP TLS Reporting policy for <em>example.com</em> must be published in DNS TXT records for <code><strong>_smtp._tls</strong>.example.com</code>.
	</em></p>

	<h2>Example SMTP TLSRPT DNS record for the <em>example.com</em> domain</h2>
	<pre><code>TXT "v=TLSRPTv1;rua=mailto:example@tlsrpt.report-uri.com"</code></pre>
	<ul>
		<li><code>v=TLSRPTv1</code>: this TXT DNS record is a SMTP TLSRPT policy record</li>
		<li><code>rua</code>: where to send <em>aggregated</em> reports to, can be a <code>mailto:</code> or <code>https:</code> URI (multiple URIs comma-separated)</li>
	</ul>
	<p>
		The <em>canhas.report</em> domain has no SMTP TLSRPT record because no mail is sent from this domain, which is indicated with
		<a href="https://toolbox.googleapps.com/apps/dig/#MX/canhas.report">Null MX record</a>,
		<a href="https://toolbox.googleapps.com/apps/dig/#TXT/canhas.report">empty SPF with <code>-all</code></a>
		and <a href="https://toolbox.googleapps.com/apps/dig/#TXT/_dmarc.canhas.report">DMARC record with <em>reject</em> policy</a>.
		My site has <a href="https://toolbox.googleapps.com/apps/dig/#TXT/_smtp._tls.michalspacek.cz">a SMTP TLSRPT record</a> similar to the example one above.
	</p>

	<h2>Example SMTP TLSRPT report</h2>
	<p>This is an aggregated report for <em>example.com</em> sent by Google:</p>
	<pre><code class="json"><?= \Can\Has\jsonReportHtml([
		'organization-name' => 'Google Inc.',
		'date-range' => [
			'start-datetime' => '2020-05-22T00:00:00Z',
			'end-datetime' => '2020-05-22T23:59:59Z',
		],
		'contact-info' => 'smtp-tls-reporting@google.com',
		'report-id' => '2020-05-22T00:00:00Z_example.com',
		'policies' => [
			[
				'policy' => [
					'policy-type' => 'sts',
					'policy-string' => [
						'version: STSv1',
						'mode: testing',
						'mx: mx1.smtp.goog',
						'mx: mx2.smtp.goog',
						'mx: mx3.smtp.goog',
						'mx: mx4.smtp.goog',
						'max_age: 86400',
					],
					'policy-domain' => 'example.com',
				],
				'summary' => [
					'total-successful-session-count' => 7,
					'total-failure-session-count' => 0,
				],
			],
		],
	]); ?></code></pre>

</div>
<?= \Can\Has\footerHtml(); ?>
</body>
