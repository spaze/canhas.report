<?php
declare(strict_types = 1);

require __DIR__ . '/../shared/functions.php';

$nonce = base64_encode(random_bytes(16));
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; report-uri " . \Can\Has\reportUrl();
$pageHeaderHtml = 'Content Security Policy with <code>report-uri</code>';
$pageDescription = 'Sending Content Security Policy violation reports';
$cspHeaderDescription = 'The CSP header';
$reportDirective = 'report-uri';
$reportDirectiveDescription = 'where to send violation reports to';
$willTriggerReportHtml = 'will trigger a report, check <em>Developer tools</em> (<em>Network</em> and <em>Console</em> tabs)';
$checkReportsHtml = 'check your <a href="' . htmlspecialchars(\Can\Has\reportOrigin()) . '/">reports</a>';
$additionalHeaderHtml = null;

header($cspHeader);
echo \Can\Has\pageHead('CSP report-uri', ['highlight.pack.js', 'highlight-init.js']);

require __DIR__ . '/../shared/csp-body.php';
