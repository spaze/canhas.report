<?php
declare(strict_types = 1);

namespace Can\Has\QualityAssuranc;

require_once __DIR__ . '/../shared/functions.php';

$_COOKIE[\Can\Has\cookieName()] = 'ace';
$_SERVER['HAS_BASE'] = 'of.ba.se';

$fileLine = __LINE__;  // don't move, used for printing line numbers
$tests = [
	\Can\Has\caaRecord() => "CAA <span class=hl-number>0</span> issue <span class=hl-string>&quot;letsencrypt.org&quot;</span>\nCAA <span class=hl-number>0</span> issue <span class=hl-string>&quot;example.com&quot;</span>\nCAA <span class=hl-number>0</span> iodef <span class=hl-string>&quot;mailto:security@example.net&quot;</span>",
	"Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-Rand0m123' 'self' 'report-sample'; style-src 'self'; form-action 'self'; report-to default" => '<span class=hl-header>Content-Security-Policy:</span> default-src <span class=hl-string>&apos;none&apos;</span>; img-src <span class=hl-string>&apos;self&apos;</span> https://www.michalspacek.cz; script-src <span class=hl-string>&apos;nonce-Rand<span class=hl-number>0</span>m<span class=hl-number>123</span>&apos;</span> <span class=hl-string>&apos;self&apos;</span> <span class=hl-string>&apos;report-sample&apos;</span>; style-src <span class=hl-string>&apos;self&apos;</span>; form-action <span class=hl-string>&apos;self&apos;</span>; report-to default',
	"Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-Rand0m123' 'self' 'report-sample'; style-src 'self'; report-to default" => '<span class=hl-header>Content-Security-Policy:</span> default-src <span class=hl-string>&apos;none&apos;</span>; img-src <span class=hl-string>&apos;self&apos;</span> https://www.michalspacek.cz; script-src <span class=hl-string>&apos;nonce-Rand<span class=hl-number>0</span>m<span class=hl-number>123</span>&apos;</span> <span class=hl-string>&apos;self&apos;</span> <span class=hl-string>&apos;report-sample&apos;</span>; style-src <span class=hl-string>&apos;self&apos;</span>; report-to default',
	"Content-Security-Policy: default-src 'none'; img-src 'self' https://www.michalspacek.cz; script-src 'nonce-Rand0m123' 'self' 'report-sample'; style-src 'self'; report-uri " . \Can\Has\reportUrl('csp/enforce') => '<span class=hl-header>Content-Security-Policy:</span> default-src <span class=hl-string>&apos;none&apos;</span>; img-src <span class=hl-string>&apos;self&apos;</span> https://www.michalspacek.cz; script-src <span class=hl-string>&apos;nonce-Rand<span class=hl-number>0</span>m<span class=hl-number>123</span>&apos;</span> <span class=hl-string>&apos;self&apos;</span> <span class=hl-string>&apos;report-sample&apos;</span>; style-src <span class=hl-string>&apos;self&apos;</span>; report-uri https://ace.of.ba.se/report',
	"Content-Security-Policy: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce') => '<span class=hl-header>Content-Security-Policy:</span> require-trusted-types-for <span class=hl-string>&apos;script&apos;</span>; report-uri https://ace.of.ba.se/report',
	"Content-Security-Policy-Report-Only: default-src data: 'self' 'nonce-foobar==' 'report-sample'; report-to default" => '<span class=hl-header>Content-Security-Policy-Report-Only:</span> default-src data: <span class=hl-string>&apos;self&apos;</span> <span class=hl-string>&apos;nonce-foobar==&apos;</span> <span class=hl-string>&apos;report-sample&apos;</span>; report-to default',
	"Content-Security-Policy-Report-Only: default-src https: data: 'unsafe-inline'; report-uri " . \Can\Has\reportUrl('csp/reportOnly') => '<span class=hl-header>Content-Security-Policy-Report-Only:</span> default-src https: data: <span class=hl-string>&apos;unsafe-inline&apos;</span>; report-uri https://ace.of.ba.se/report',
	"Content-Security-Policy-Report-Only: require-trusted-types-for 'script'; report-uri " . \Can\Has\reportUrl('csp/enforce') => '<span class=hl-header>Content-Security-Policy-Report-Only:</span> require-trusted-types-for <span class=hl-string>&apos;script&apos;</span>; report-uri https://ace.of.ba.se/report',
	\Can\Has\dmarcRecord() => 'TXT <span class=hl-string>&quot;<span class=hl-attr>v=</span>DMARC<span class=hl-number>1</span>; <span class=hl-attr>p=</span>quarantine; <span class=hl-attr>rua=</span>mailto:example@dmarc.report-uri.com,mailto:security@example.com; <span class=hl-attr>ruf=</span>mailto:security@example.com&quot;</span>',
	file_get_contents(__DIR__ . '/../shared/dmarc-example.xml') => "<span class=hl-meta>&lt;?xml <span class=hl-attr>version=</span><span class=hl-string>&quot;<span class=hl-number>1.0</span>&quot;</span>?&gt;</span></span>\n<span class=hl-tag>&lt;feedback&gt;</span>\n  <span class=hl-tag>&lt;report_metadata&gt;</span>\n    <span class=hl-tag>&lt;org_name&gt;</span>google.com<span class=hl-tag>&lt;/org_name&gt;</span>\n    <span class=hl-tag>&lt;email&gt;</span>noreply-dmarc-support@google.com<span class=hl-tag>&lt;/email&gt;</span>\n    <span class=hl-tag>&lt;extra_contact_info&gt;</span>https://support.google.com/a/answer/<span class=hl-number>2466580</span><span class=hl-tag>&lt;/extra_contact_info&gt;</span>\n    <span class=hl-tag>&lt;report_id&gt;</span><span class=hl-number>94914750191783696133</span><span class=hl-tag>&lt;/report_id&gt;</span>\n    <span class=hl-tag>&lt;date_range&gt;</span>\n      <span class=hl-tag>&lt;begin&gt;</span><span class=hl-number>1589673600</span><span class=hl-tag>&lt;/begin&gt;</span>\n      <span class=hl-tag>&lt;end&gt;</span><span class=hl-number>1589759999</span><span class=hl-tag>&lt;/end&gt;</span>\n    <span class=hl-tag>&lt;/date_range&gt;</span>\n  <span class=hl-tag>&lt;/report_metadata&gt;</span>\n  <span class=hl-tag>&lt;policy_published&gt;</span>\n    <span class=hl-tag>&lt;domain&gt;</span>example.com<span class=hl-tag>&lt;/domain&gt;</span>\n    <span class=hl-tag>&lt;adkim&gt;</span>r<span class=hl-tag>&lt;/adkim&gt;</span>\n    <span class=hl-tag>&lt;aspf&gt;</span>r<span class=hl-tag>&lt;/aspf&gt;</span>\n    <span class=hl-tag>&lt;p&gt;</span>quarantine<span class=hl-tag>&lt;/p&gt;</span>\n    <span class=hl-tag>&lt;sp&gt;</span>quarantine<span class=hl-tag>&lt;/sp&gt;</span>\n    <span class=hl-tag>&lt;pct&gt;</span><span class=hl-number>100</span><span class=hl-tag>&lt;/pct&gt;</span>\n  <span class=hl-tag>&lt;/policy_published&gt;</span>\n  <span class=hl-tag>&lt;record&gt;</span>\n    <span class=hl-tag>&lt;row&gt;</span>\n      <span class=hl-tag>&lt;source_ip&gt;</span><span class=hl-number>192.0.2.1</span><span class=hl-tag>&lt;/source_ip&gt;</span>\n      <span class=hl-tag>&lt;count&gt;</span><span class=hl-number>1</span><span class=hl-tag>&lt;/count&gt;</span>\n      <span class=hl-tag>&lt;policy_evaluated&gt;</span>\n        <span class=hl-tag>&lt;disposition&gt;</span>none<span class=hl-tag>&lt;/disposition&gt;</span>\n        <span class=hl-tag>&lt;dkim&gt;</span>pass<span class=hl-tag>&lt;/dkim&gt;</span>\n        <span class=hl-tag>&lt;spf&gt;</span>pass<span class=hl-tag>&lt;/spf&gt;</span>\n      <span class=hl-tag>&lt;/policy_evaluated&gt;</span>\n    <span class=hl-tag>&lt;/row&gt;</span>\n    <span class=hl-tag>&lt;identifiers&gt;</span>\n      <span class=hl-tag>&lt;header_from&gt;</span>example.com<span class=hl-tag>&lt;/header_from&gt;</span>\n    <span class=hl-tag>&lt;/identifiers&gt;</span>\n    <span class=hl-tag>&lt;auth_results&gt;</span>\n      <span class=hl-tag>&lt;dkim&gt;</span>\n        <span class=hl-tag>&lt;domain&gt;</span>example.com<span class=hl-tag>&lt;/domain&gt;</span>\n        <span class=hl-tag>&lt;result&gt;</span>pass<span class=hl-tag>&lt;/result&gt;</span>\n        <span class=hl-tag>&lt;selector&gt;</span>google<span class=hl-tag>&lt;/selector&gt;</span>\n      <span class=hl-tag>&lt;/dkim&gt;</span>\n      <span class=hl-tag>&lt;spf&gt;</span>\n        <span class=hl-tag>&lt;domain&gt;</span>example.com<span class=hl-tag>&lt;/domain&gt;</span>\n        <span class=hl-tag>&lt;result&gt;</span>pass<span class=hl-tag>&lt;/result&gt;</span>\n      <span class=hl-tag>&lt;/spf&gt;</span>\n    <span class=hl-tag>&lt;/auth_results&gt;</span>\n  <span class=hl-tag>&lt;/record&gt;</span>\n  <span class=hl-tag>&lt;record&gt;</span>\n    <span class=hl-tag>&lt;row&gt;</span>\n      <span class=hl-tag>&lt;source_ip&gt;</span><span class=hl-number>198.51.100.123</span><span class=hl-tag>&lt;/source_ip&gt;</span>\n      <span class=hl-tag>&lt;count&gt;</span><span class=hl-number>1</span><span class=hl-tag>&lt;/count&gt;</span>\n      <span class=hl-tag>&lt;policy_evaluated&gt;</span>\n        <span class=hl-tag>&lt;disposition&gt;</span>quarantine<span class=hl-tag>&lt;/disposition&gt;</span>\n        <span class=hl-tag>&lt;dkim&gt;</span>fail<span class=hl-tag>&lt;/dkim&gt;</span>\n        <span class=hl-tag>&lt;spf&gt;</span>fail<span class=hl-tag>&lt;/spf&gt;</span>\n      <span class=hl-tag>&lt;/policy_evaluated&gt;</span>\n    <span class=hl-tag>&lt;/row&gt;</span>\n    <span class=hl-tag>&lt;identifiers&gt;</span>\n      <span class=hl-tag>&lt;header_from&gt;</span>example.com<span class=hl-tag>&lt;/header_from&gt;</span>\n    <span class=hl-tag>&lt;/identifiers&gt;</span>\n    <span class=hl-tag>&lt;auth_results&gt;</span>\n      <span class=hl-tag>&lt;spf&gt;</span>\n        <span class=hl-tag>&lt;domain&gt;</span>example.com<span class=hl-tag>&lt;/domain&gt;</span>\n        <span class=hl-tag>&lt;scope&gt;</span>mfrom<span class=hl-tag>&lt;/scope&gt;</span>\n        <span class=hl-tag>&lt;result&gt;</span>softfail<span class=hl-tag>&lt;/result&gt;</span>\n      <span class=hl-tag>&lt;/spf&gt;</span>\n    <span class=hl-tag>&lt;/auth_results&gt;</span>\n  <span class=hl-tag>&lt;/record&gt;</span>\n<span class=hl-tag>&lt;/feedback&gt;</span>\n",
	'Expect-CT: max-age=' . \Can\Has\maxAge() . ', enforce, report-uri="' . \Can\Has\reportUrl('ct/enforce') . '"' => '<span class=hl-header>Expect-CT:</span> max-age=<span class=hl-number>1800</span>, enforce, report-uri=<span class=hl-string>&quot;https://ace.of.ba.se/report&quot;</span>',
	\Can\Has\nelHeader() => '<span class=hl-header>NEL:</span> {<span class=hl-key>&quot;report_to&quot;:</span><span class=hl-string>&quot;default&quot;</span>,<span class=hl-key>&quot;max_age&quot;:</span><span class=hl-number>1800</span>,<span class=hl-key>&quot;include_subdomains&quot;:</span>true}',
	'Permissions-Policy-Report-Only: fullscreen=()' => '<span class=hl-header>Permissions-Policy-Report-Only:</span> <span class=hl-attr>fullscreen=</span>()',
	'Permissions-Policy: fullscreen=(self "https://exploited.cz")' => '<span class=hl-header>Permissions-Policy:</span> <span class=hl-attr>fullscreen=</span>(self <span class=hl-string>&quot;https://exploited.cz&quot;</span>)',
	'Permissions-Policy: geolocation=(), fullscreen=(), camera=(self "https://www.michalspacek.com"), midi=*' => '<span class=hl-header>Permissions-Policy:</span> <span class=hl-attr>geolocation=</span>(), <span class=hl-attr>fullscreen=</span>(), <span class=hl-attr>camera=</span>(self <span class=hl-string>&quot;https://www.michalspacek.com&quot;</span>), <span class=hl-attr>midi=</span>*',
];

$errors = $total = 0;
foreach ($tests as $input => $expected) {
	$total++;
	$actual = \Can\Has\highlight($input);
	if ($actual !== $expected) {
		printf("Error on line %d: `%s` should be `%s`\n", $fileLine + $total + 1, $actual, $expected);
		$errors++;
	}
}
printf("Total %d tests, %d errors\n", $total, $errors);
exit($errors ? 1 : 0);
