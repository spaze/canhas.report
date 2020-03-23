<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$nonce = base64_encode(random_bytes(16));
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; report-to default";
$pageHeaderHtml = 'Content Security Policy with <code>report-to</code>';
$reportToHeader = \Can\Has\reportToHeader();
$pageDescription = 'Sending Content Security Policy violation reports with Reporting API using the Report-To header, asynchronously and out-of-band, when the browser feels like';
$cspHeaderDescription = 'The CSP header';
$reportDirective = 'report-to';
$reportDirectiveDescription = 'name of the group where to send violation reports to';
$willTriggerReportHtml = \Can\Has\willTriggerReportToHtml();
$checkReportsHtml = \Can\Has\checkReportsReportToHtml();
$additionalHeaderHtml = \Can\Has\reportToHeaderHtml($reportToHeader);

header($cspHeader);
header($reportToHeader);
echo \Can\Has\pageHead('CSP report-to', ['highlight.pack.js', 'highlight-init.js']);

require __DIR__ . '/../shared/csp-body.php';
