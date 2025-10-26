<?php
declare(strict_types = 1);

$nonce = \Can\Has\randomNonce();
$cspHeader = "Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-{$nonce}' 'self' 'report-sample'; style-src 'self' 'nonce-{$nonce}'; report-to default";
$pageHeaderHtml = 'Content Security Policy with <code>report-to</code>';
$reportingEndpointsHeader = \Can\Has\reportingEndpointsHeader();
$pageDescriptionHtml = 'Sending Content Security Policy (CSP) violation reports with Reporting API using the <code>Reporting-Endpoints</code> header, asynchronously and out-of-band, when the browser feels like.<br><br>
	CSP is a policy that lets the authors (or server administrators) of a web application inform the browser about the sources from which the application expects to load resources like images, scripts, styles, or even where to submit forms.
	Content Security Policy is not intended as a first line of defense against content injection vulnerabilities like Cross-Site Scripting (XSS). Instead, CSP is best used as defense-in-depth, to reduce the harm caused by content injection attacks.
	The <code>report-to</code> directive using the Reporting API replaces the deprecated <code>report-uri</code> directive in Content Security Policy level 3 spec, which is not yet fully supported by all major clients.
	To support more browsers, apps usually send both <code>report-uri</code> and <code>report-to</code> in their CSP headers.';
$includeReportingApiNotSupportedWarning = true;
$cspHeaderDescription = 'The CSP response header';
$reportDirectiveDescriptionHtml = \Can\Has\reportToDirectiveDescriptionHtml();
$willTriggerReportHtml = \Can\Has\willTriggerReportToHtml();
$checkReportsHtml = \Can\Has\checkReportsReportToHtml();
$additionalHeaderHtml = \Can\Has\reportingEndpointsHeaderHtml($reportingEndpointsHeader, 'the same as in the CSP header in the <code>report-to</code> directive');
$specs = ['csp', 'reporting-api'];

header($cspHeader);
header($reportingEndpointsHeader);
?>
<!DOCTYPE html>
<html lang="en">
<?php
echo \Can\Has\pageHead('CSP report-to', $nonce);
require __DIR__ . '/../shared/csp-body.php';
?>
</html>
