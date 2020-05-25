<?php
declare(strict_types = 1);

$nonce = base64_encode(random_bytes(16));
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; report-to default";
$pageHeaderHtml = 'Content Security Policy with <code>report-to</code>';
$reportToHeader = \Can\Has\reportToHeader();
$pageDescriptionHtml = 'Sending Content Security Policy (CSP) violation reports with Reporting API using the <code>Report-To</code> header, asynchronously and out-of-band, when the browser feels like, possibly grouped with other reports and even other report types.<br><br>
	CSP is a policy that lets the authors (or server administrators) of a web application inform the browser about the sources from which the application expects to load resources like images, scripts, styles, or even where to submit forms.
	Content Security Policy is not intended as a first line of defense against content injection vulnerabilities like Cross-Site Scripting (XSS). Instead, CSP is best used as defense-in-depth, to reduce the harm caused by content injection attacks. 
	The <code>report-to</code> directive using the Reporting API replaces the deprecated <code>report-uri</code> directive in Content Security Policy level 3 spec, which is not yet fully supported by all major clients.
	To support more browsers, apps usually send both <code>report-uri</code> and <code>report-to</code> in their CSP headers.';
$includeReportingApiNotSupportedWarning = true;
$cspHeaderDescription = 'The CSP response header';
$reportDirective = 'report-to';
$reportDirectiveDescription = 'name of the group where to send violation reports to';
$willTriggerReportHtml = \Can\Has\willTriggerReportToHtml();
$checkReportsHtml = \Can\Has\checkReportsReportToHtml();
$additionalHeaderHtml = \Can\Has\reportToHeaderHtml($reportToHeader, 'the same as in the CSP header in the <code>report-to</code> directive');
$specs = ['csp', 'reporting-api'];

header($cspHeader);
header($reportToHeader);
echo \Can\Has\pageHead('CSP report-to');

require __DIR__ . '/../shared/csp-body.php';
