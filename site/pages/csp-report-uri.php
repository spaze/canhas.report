<?php
declare(strict_types = 1);

$nonce = base64_encode(random_bytes(16));
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self'; report-uri " . \Can\Has\reportUrl('csp/enforce');
$pageHeaderHtml = 'Content Security Policy with <code>report-uri</code>';
$pageDescriptionHtml = 'Sending Content Security Policy (CSP) violation reports.<br><br>
	CSP is a policy that lets the authors (or server administrators) of a web application inform the browser about the sources from which the application expects to load resources like images, scripts, styles, or even where to submit forms.
	Content Security Policy is not intended as a first line of defense against content injection vulnerabilities like Cross-Site Scripting (XSS). Instead, CSP is best used as defense-in-depth, to reduce the harm caused by content injection attacks. 
	Using <code>report-uri</code> directive is specific to CSP and is not part of the Reporting API specification, and is actually deprecated and replaced by <code>report-to</code> directive and Reporting API in Content Security Policy level 3 spec, which is not yet fully supported by all major clients.
	To support more browsers, apps usually send both <code>report-uri</code> and <code>report-to</code> in their CSP headers.';
$includeReportingApiNotSupportedWarning = false;
$cspHeaderDescription = 'The CSP response header';
$reportDirective = 'report-uri';
$reportDirectiveDescription = 'where to send violation reports to';
$willTriggerReportHtml = 'Will trigger a report, check <em>Developer tools</em> (<em>Network</em> and <em>Console</em> tabs)';
$checkReportsHtml = 'Check your <a href="' . htmlspecialchars(\Can\Has\reportViewer()) . '/">reports</a>';
$additionalHeaderHtml = null;
$specs = ['csp'];

header($cspHeader);
echo \Can\Has\pageHead('CSP report-uri');

require __DIR__ . '/../shared/csp-body.php';
