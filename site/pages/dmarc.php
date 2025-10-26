<?php
declare(strict_types = 1);
?>
<!DOCTYPE html>
<html lang="en">
<?= \Can\Has\pageHead('Domain-based Message Authentication, Reporting and Conformance (DMARC)'); ?>
<body>
<?= \Can\Has\headerHtml('Reporting Demos'); ?>
<div id="main">
<div class="content">
	<?= \Can\Has\bookmarks('index'); ?>

	<h1>Domain-based Message Authentication, Reporting and Conformance (DMARC) reports</h1>
	<p><em>
		Domain-based Message Authentication, Reporting and Conformance (DMARC) allows domain owners to communicate to email receivers what they should do with messages that fail to authenticate
		using Sender Policy Framework (SPF) and/or DomainKeys Identified Mail (DKIM). You can also employ DMARC to request reporting of authentication and disposition results of received mail.
		The DMARC policy for <em>example.com</em> must be published in DNS TXT records for <code><strong>_dmarc</strong>.example.com</code>.
	</em></p>

	<h2>Example DMARC DNS record for the <em>example.com</em> domain</h2>
	<pre><code class="hl-dmarc"><?= \Can\Has\highlight(\Can\Has\dmarcRecord()) ?></code></pre>
	<ul>
		<li><code>v=DMARC1</code>: this TXT DNS record is a DMARC policy record</li>
		<li><code>p=quarantine</code>: mail receivers should quarantine messages from example.com that fail authentication, usually means "place into spam folder", other policies are <code>none</code> and <code>reject</code></li>
		<li><code>rua</code>: where to send <em>aggregated</em> reports, can be any valid URI (multiple URIs comma-separated) but only <code>mailto:</code> is guaranteed to be universally supported</li>
		<li><code>ruf</code>: one or more URIs where to send message-specific detailed <em>failure</em> reports, only <code>mailto:</code> is guaranteed to be universally supported</li>
	</ul>
	<p>
		The email address in <code>rua</code> and <code>ruf</code> should be the same as the domain where the DMARC policy was found at, which is the case for the failure reports in the example above.
		If it's not the case, as in the <code>rua</code> example, the mail receiver should check whether it can send the <em>example.com</em> report to <em>dmarc.report-uri.com</em> host by querying
		<code>example.com.<strong>_report._dmarc</strong>.dmarc.report-uri.com</code> DNS TXT records and checking for one that starts with <code>v=DMARC1</code>. The <em>dmarc.report-uri.com</em> host can signal that it is willing
		to receive reports for any domain by publishing a wildcard record <a href="https://toolbox.googleapps.com/apps/dig/#TXT/*._report._dmarc.dmarc.report-uri.com"><code><strong>*._report._dmarc</strong>.dmarc.report-uri.com</code></a>.
		These checks are the reason you cannot send your DMARC reports to Gmail and possibly other free email services because they <a href="https://toolbox.googleapps.com/apps/dig/#TXT/*._report._dmarc.gmail.com">don't publish</a> the required DNS records.
	</p>
	<p>
		The <em>canhas.report</em> domain has a <a href="https://toolbox.googleapps.com/apps/dig/#TXT/_dmarc.canhas.report">DMARC DNS record</a> similar to the example above
		and <em>michalspacek.cz</em> publishes the <a href="https://toolbox.googleapps.com/apps/dig/#TXT/canhas.report._report._dmarc.michalspacek.cz">required DNS record</a> for the external reporting to succeed.
	</p>

	<h2>Example DMARC report</h2>
	<p>This is an aggregated report for <em>example.com</em> sent by Google, with two actual reports, one that has passed authentication and the other has failed both DKIM and SPF checks:</p>
	<pre><code><?= \Can\Has\highlight(file_get_contents(__DIR__ . '/../shared/dmarc-example.xml')); ?></code></pre>

	<?= \Can\Has\specsHtml('dmarc', 'spf', 'dkim'); ?>
</div>
</div>
<?= \Can\Has\footerHtml(); ?>
</body>
</html>
