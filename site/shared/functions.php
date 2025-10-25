<?php
declare(strict_types = 1);

namespace Can\Has;

function bookmarks(string ...$links): string
{
	$hrefs = [];
	foreach ($links as $link) {
		switch ($link) {
			case 'index':
				$hrefs[] = '<a href="' . \htmlspecialchars(baseOrigin()) . '/">↩ Back</a>';
				break;
			case 'reports':
				$hrefs[] = \sprintf('<a href="%s/">%s</a>', \htmlspecialchars(reportViewer()), reportToReportUri() ? 'Report URI Reports' : 'Reports');
				break;
		}
	}
	return '<div id="bookmarks"><div>' . \implode(' ', $hrefs) . '</div></div>';
}


function pageHead(?string $title = null, ?string $nonce = null): string
{
	return '<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>' . ($title ? "{$title} | " : '') . 'Reporting API Demos</title>
		<style' . ($nonce ? ' nonce="' . \htmlspecialchars($nonce) . '"' : '') . '>
			body { font: 1rem/1.7 Arial, sans-serif; }
			img { max-width: 100%; height: auto; }
			pre { overflow-x: auto; }
		</style>
		<link rel="icon" type="image/svg+xml" href="' . \htmlspecialchars(baseOrigin()) . '/assets/favicon.svg">
		<link rel="alternate icon" href="' . \htmlspecialchars(baseOrigin()) . '/assets/favicon.png">
		<link rel="stylesheet" href="' . \htmlspecialchars(baseOrigin()) . '/assets/style.css">
		<script src="' . \htmlspecialchars(baseOrigin()) . '/assets/scripts.js"></script>
		</head>';
}


function smallReportUriLogoHtml(): string
{
	return '<a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener"><img src="' . \htmlspecialchars(baseOrigin()) . '/assets/report-uri.svg" alt="report-uri.com logo" width="120" height="21" class="supported-by-inline"></a>';
}


function headerHtml(string $header): string
{
	return '<div id="header">
		<div class="content">
		<a href="' . \htmlspecialchars(baseOrigin()) . '/"><strong>' . \htmlspecialchars($header) . '</strong></a> <span><span class="separator">&mdash;</span><span class="separator-break"></span> Supported by ' . smallReportUriLogoHtml() . '</span>
		</div>
		</div>';
}


function footerHtml(): string
{
	return '<div id="footer">
		<div class="content">
		By <a href="https://www.michalspacek.com">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">@spazef0rze</a>,
		supported by ' . smallReportUriLogoHtml() . ' &ndash; real time security monitoring and error tracking
		</div>
	</div>';
}


function redirectToBase(): void
{
	\header('Location: ' . baseOrigin() . '/');
	exit;
}


function cookieName(): string
{
	return 'who';
}


function cookieNameReportUri(): string
{
	return 'report-uri';
}


function cookieNameEndpoint(): string
{
	return 'endpoint';
}


function cookieValueEndpointReportUri(): string
{
	return 'report-uri';
}


function reportToReportUri(): bool
{
	$value = $_COOKIE[cookieNameEndpoint()] ?? null;
	return $value === cookieValueEndpointReportUri() && cookieReportUri() !== null;
}


function setCookie(string $name, string $value, bool $httpOnly): void
{
	\setcookie($name, $value, [
		'expires' => \strtotime('1 year'),
		'secure' => true,
		'httponly' => $httpOnly,
		'samesite' => 'Lax',
	]);
}


function cookie(): string
{
	$name = cookieName();
	$who = $_COOKIE[$name] ?? null;
	if ($who === null || \preg_match('/^[a-z0-9-]+$/', $who) !== 1) {
		$_COOKIE[$name] = $who = randomSubdomain();
		setCookie($name, $who, false);
	}
	return $who;
}


function cookieReportUri(): ?string
{
	$name = cookieNameReportUri();
	$who = $_COOKIE[$name] ?? null;
	if ($who !== null && \preg_match('/^[a-z0-9]+$/', $who) !== 1) {
		$_COOKIE[$name] = $who = null;
	}
	return $who;
}


function who(): ?string
{
	return $_SERVER['HAS_SUBDOMAIN'] ?: null;
}


function baseHostname(): string
{
	return $_SERVER['CAN_HAS_BASE'];
}


function baseHostnameReportUri(): string
{
	return 'report-uri.com';
}


function baseOrigin(): string
{
	return 'https://' . baseHostname();
}


function baseSubdomainOrigin(string $subdomain): string
{
	return "https://{$subdomain}." . baseHostname();
}


function reportOrigin(): string
{
	return 'https://' . cookie() . ".{$_SERVER['HAS_BASE']}";
}


function reportCanHasOrigin(string $who): string
{
	return "https://{$who}.{$_SERVER['HAS_BASE']}";
}


function reportViewer(): string
{
	return reportToReportUri() ? 'https://' . baseHostnameReportUri() . '/account' : reportOrigin();
}


function reportUrlCanHas(): string
{
	return reportOrigin() . '/report';
}


function reportUrl(?string $type = null): string
{
	if (reportToReportUri()) {
		return 'https://' . cookieReportUri() . '.' . baseHostnameReportUri() . '/' . ($type === null ? 'a/d/g' : "r/d/{$type}");
	} else {
		return reportUrlCanHas();
	}
}


function jsonReportHtml(array $data): string
{
	return highlight(
		\preg_replace(
			'/^(  +?)\\1(?=[^ ])/m',
			'$1',
			\rawurldecode(\json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
		)
	);
}


function reports(\PDOStatement $statement, ?int &$seen): string
{
	$rowCount = $statement->rowCount();
	if ($rowCount === 0) {
		return '<p>No reports yet</p>';
	}

	$result = [];
	$cookieSeen = $_COOKIE['seen'] ?? 0;
	$newCount = 0;
	$newestReceived = $oldestReceived = null;
	foreach ($statement as $row) {
		if ($seen === null) {
			$seen = \strtotime($row['received']);
		}
		if ($newestReceived === null) {
			$newestReceived = $row['received'];
		}
		$oldestReceived = $row['received'];
		$counts = \array_count_values(\json_decode($row['types']));
		$types = [];
		foreach ($counts as $type => $count) {
			$types[] = "{$count}× $type";
		}
		$reports = \json_decode($row['report'], true);
		$who = (isset($row['who']) ? \htmlspecialchars($row['who']) : null);
		$isNew = strtotime($row['received']) > $cookieSeen;
		if ($isNew) {
			$newCount++;
		}
		$result[] = \sprintf('<p>%s%s <small>%s</small> <strong>%s</strong>%s%s</p><pre><code>%s</code></pre>',
			($isNew ? '🆕 ' : ''),
			\htmlspecialchars($row['received']),
			\htmlspecialchars(\date_default_timezone_get()),
			\htmlspecialchars(\implode(' + ', $types)),
			(isset($reports[0]) && \is_array($reports[0]) ? ' via Reporting API' : ''),
			(isset($who) ? ' from <code><a href="' . reportCanHasOrigin($who) . '"><strong>' . $who . '</strong></a></code>' : ''),
			jsonReportHtml($reports)
		);
	}
	unset($row, $counts, $types, $count, $type, $reports, $who);

	$dates = '';
	if ($rowCount > 1) {
		$now = new \DateTimeImmutable();
		$newest = getInterval(new \DateTimeImmutable($newestReceived)->diff($now));
		$oldest = getInterval(new \DateTimeImmutable($oldestReceived)->diff($now));
		if ($newest === $oldest) {
			$dates = \sprintf('; %s are %s old', ($rowCount === 2 ? 'both' : 'all'), $newest);
		} else {
			$dates = \sprintf('; the newest is %s old, the oldest is %s old', $newest, $oldest);
		}
	}
	$counts = \sprintf('<p><em>%s new %s, %s %s total%s</em></p>',
		\htmlspecialchars($newCount === 0 ? 'No' : (string)$newCount),
		\htmlspecialchars($newCount === 1 ? 'report' : 'reports'),
		\htmlspecialchars((string)$rowCount),
		\htmlspecialchars($rowCount > 1 ? 'reports' : 'report'),
		\htmlspecialchars($dates),
	);
	return $counts . \implode("\n", $result);
}


function getInterval(\DateInterval $interval): string
{
	$age = '';
	if ($interval->y !== 0) {
		$age = "{$interval->y} " . ($interval->y === 1 ? 'year' : 'years');
	} elseif ($interval->m !== 0) {
		$age = "{$interval->m} " . ($interval->m === 1 ? 'month' : 'months');
	} elseif ($interval->d !== 0) {
		$age = "{$interval->d} " . ($interval->d === 1 ? 'day' : 'days');
	} elseif ($interval->h !== 0) {
		$age = "{$interval->h} " . ($interval->h === 1 ? 'hour' : 'hours');
	} elseif ($interval->i !== 0) {
		$age = "{$interval->i} " . ($interval->i === 1 ? 'minute' : 'minutes');
	} elseif ($interval->s !== 0) {
		$age = "{$interval->s} " . ($interval->s === 1 ? 'second' : 'seconds');
	}
	return $age;
}


function maxAge(): int
{
	return 30 * 60;
}


function reportToDirectiveDescriptionHtml(): string
{
	return '<code>report-to</code>: name of the endpoint where to send violation reports, as defined in the <code>Reporting-Endpoints</code> header';
}


function reportToHeader(): string
{
	$reportTo = [
		'group' => 'default',
		'max_age' => maxAge(),
		'endpoints' => [
			[
				'url' => reportUrl(),
			]
		],
		'include_subdomains' => true,
	];
	return 'Report-To: ' . \json_encode($reportTo, JSON_UNESCAPED_SLASHES);
}


function reportingEndpointsHeader(): string
{
	return 'Reporting-Endpoints: default="' . reportUrl() . '"';
}


function reportToHeaderHtml(string $header, string $groupDescriptionHtml): string
{
	return '<h2>The <code>Report-To</code> response header:</h2>
		<pre><code>' . highlight($header) . '</code></pre>
		<ul>
			<li><code>group</code>: the name of the group, ' . $groupDescriptionHtml .  '</li>
			<li><code>max_age</code>: how long the browser should use the endpoint and report errors to it</li>
			<li>
				<code>endpoints</code>: reporting endpoint configuration, can specify multiple endpoints but reports will be sent to just one of them
				<ul>
					<li><code>url</code>: where to send reports to, must be <code>https://</code>, otherwise the endpoint will be ignored</li>
				</ul>
			</li>
		</ul>';
}


function reportingEndpointsHeaderHtml(string $header, string $endpointNameDescriptionHtml): string
{
	return '<h2>The <code>Reporting-Endpoints</code> response header:</h2>
		<pre><code>' . highlight($header) . '</code></pre>
		<ul>
			<li><code>default</code>: the name of the endpoint, ' . $endpointNameDescriptionHtml .  '</li>
			<li><code>"<em>url</em>"</code>: where to send reports to, must be <code>https://</code>, otherwise the endpoint will be ignored</li>
			<li>
				You may provide multiple <code><em>name</em>="<em>url</em>"</code> endpoints separated by comma (<code>,</code>)
				<ul>
					<li><small>For example: <code>Reporting-Endpoints: csp-reporting="https://example.com/csp", nel-reporting="https://example.com/nel"</code></small></li>
				</ul>
			</li>
		</ul>';
}


function nelHeader(): string
{
	$nel = [
		'report_to' => 'default',
		'max_age' => maxAge(),
		'include_subdomains' => true,
//	'success_fraction' => 0.5,  // 0.0-1.0, optional, no success reports if not present
//	'failure_fraction' => 0.5,  // 0.0-1.0, optional, all failure reports if not present
	];
	return 'NEL: ' . \json_encode($nel, JSON_UNESCAPED_SLASHES);
}


function caaRecord(): string
{
	return 'CAA 0 issue "letsencrypt.org"' . "\n"
		. 'CAA 0 issue "example.com"' . "\n"
		. 'CAA 0 iodef "mailto:security@example.net"';
}


function dmarcRecord(): string
{
	return 'TXT "v=DMARC1; p=quarantine; rua=mailto:example@dmarc.report-uri.com,mailto:security@example.com; ruf=mailto:security@example.com"';
}


function willTriggerReportUriHtml(): string
{
	return 'Will trigger a report, check <em>Developer tools</em> (<em>Network</em> and <em>Console</em> tabs)';
}


function willTriggerReportToHtml(string $what = 'violation'): string
{
	return "Will trigger a report that will be sent asynchronously, possibly grouped with other reports ({$what} visible in Developer Tools in the <em>Console</em> tab, you won't see the report in <em>Network</em> tab but you can still"
		. ' <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header#chrome-71-and-newer">view the reporting requests</a>)';
}


function checkReportsReportUriHtml(): string
{
	return 'Check your <a href="' . \htmlspecialchars(reportViewer()) . '/">reports</a>';
}


function checkReportsReportToHtml(): string
{
	return 'Check your <a href="' . \htmlspecialchars(reportViewer()) . '/">reports</a> (can take some time before the browser sends the report)';
}


function reportingApiNotSupportedHtml(string $messageSuffix = 'reporting will not work'): string
{
	return '<div class="reporting-api not-supported hidden">'
		. '😥 Your browser <a href="https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API#Browser_compatibility">does not support</a> Reporting API, '
		. $messageSuffix
		. '</div>';
}


function trustedTypesNotSupportedHtml(string $messageSuffix = 'the demo will not work'): string
{
	return '<div class="trusted-types not-supported hidden">'
		. '😥 Your browser <a href="https://developer.mozilla.org/en-US/docs/Web/API/TrustedHTML#browser_compatibility">does not support</a> Trusted Types, '
		. $messageSuffix
		. '</div>';
}


function trustedTypesCspHeaderDescriptionHtml(): string
{
	return '<ul>
		<li>
			<code>' . highlight("require-trusted-types-for 'script'") . '</code>: enable Trusted Types for <abbr title="Document Object Model">DOM</abbr> <abbr title="Cross-Site Scripting">XSS</abbr> sinks (<code>' . highlight("'script'") . '</code> is the only available value)
		</li>
		<li>
			<code>report-uri</code>: where to send violation reports to
			<ul>
				<li><small>Reporting would also work with the <code>report-to</code> directive, see the <a href="csp-report-to">CSP demo</a>, but let\'s keep things simple here</small></li>
			</ul>
		</li>
	</ul>';
}


function permissionsPolicyFirstPartyReportsHtml(): string
{
	return '<p>Only <em>first-party reports</em> will be sent, no reports for violations that happened in embedded iframes.</p>';
}


function permissionsPolicyNotSupportedHtml(string $messageSuffix = 'all features will work as usual'): string
{
	return '<div class="permissions-policy not-supported hidden">'
		. '🍌 Your browser <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy#browser_compatibility">does not</a> support Permissions Policy</a>, '
		. $messageSuffix
		. '</div>';
}


function scriptSourceHtmlStart(string $class): bool
{
	static $counter = 0;

	return \ob_start(function (string $source) use ($class, &$counter): string {
		// Remove the "global" indentation
		\preg_match('/^(\t*)/', $source, $matches);
		$source = \preg_replace("/^{$matches[1]}/m", '', $source);
		// Convert tabs to spaces
		do {
			$source = \preg_replace("/^( *){$matches[1][0]}/m", '$1  ', $source, -1, $count);
		} while ($count > 0);

		return $source . '<p><a href="#source' . ++$counter . '" class="view-source ' . \htmlspecialchars($class) . '" data-text-hide="hide the code" data-text-show="show the code" data-arrow-hide="▲" data-arrow-show="▼"><span class="text">show the code</span> <span class="arrow">▼</span></a></p>
			<pre id="source' . $counter . '" hidden><code>' . highlight($source) . '</code></pre>';
	});
}


function scriptSourceHtmlEnd(): bool
{
	return \ob_end_flush();
}


/**
 * Is this a good idea?
 *
 * Probably isn't but the site also has CSP in case escaping goes sideways.
 * @param string $text
 * @return string
 */
function highlight(string $text): string
{
	// hl-* classes not enclosed in " to avoid escaping them
	$replace = [
		'/&/' => '&amp;',
		'/([ "])(\w+=)/' => "$1\x11$2\x11",
		'/>/' => "&gt;\x12/span>",
		'~<~' => '&lt;',
		'~&lt;(/?\w+)~' => '<span class=hl-tag>&lt;$1',
		"~\x12/span>~" => '</span>',
		'/(&lt;\?[^?]+\?&gt;)/' => '<span class=hl-meta>$1</span>',
		"/\x11([^\x11]+)\x11/" => '<span class=hl-attr>$1</span>',
		'/(\d+[\d.]*)/' => '<span class=hl-number>$1</span>',
		"/'([^']+)'/" => '<span class=hl-string>&apos;$1&apos;</span>',
		'/"([^"]+)":/' => '<span class=hl-key>&quot;$1&quot;:</span>',
		'/\\\\"/' => '\&quot;',
		'/"([^"]*)"/' => '<span class=hl-string>&quot;$1&quot;</span>',
		'/^([a-z0-9-]+:)/i' => '<span class=hl-header>$1</span>',
		// need to escape unpaired quotes too
		"/'/" => '&apos;',
		'/"/' => '&quot;',
	];
	return preg_replace(array_keys($replace), $replace, $text);
}


function randomSubdomain(): string
{
	$subdomains = require __DIR__ . '/subdomains.php';
	return $subdomains[\array_rand($subdomains)];
}


function dataRetentionDays(): int
{
	return 30;
}


function randomNonce(): string
{
	return base64_encode(random_bytes(18));
}


function specsHtml(string ...$specs): string
{
	$hrefs = [];
	foreach ($specs as $spec) {
		switch ($spec) {
			case 'csp':
				$hrefs[] = <<<'EOT'
					<a href="https://www.w3.org/TR/CSP3/">Content Security Policy Level 3</a> Working Draft
					<ul>
						<li><small><a href="https://www.w3.org/TR/CSP2/">Content Security Policy Level 2</a></small></li>
						<li><small><a href="https://www.w3.org/TR/CSP1/">Content Security Policy 1.0</a> (discontinued)</small></li>
					</ul>
				EOT;
				break;
			case 'reporting-api':
				$hrefs[] = <<< 'EOT'
					<a href="https://www.w3.org/TR/reporting/">Reporting API</a> Working Draft
					<ul>
						<li><small><a href="https://w3c.github.io/reporting/">Reporting API</a> Editor's Draft (which will evolve into a Working Draft, followed by a Recommendation eventually)</small></li>
					</ul>
				EOT;
				break;
			case 'crash':
				$hrefs[] = '<a href="https://wicg.github.io/crash-reporting/">Crash Reporting</a> Draft Community Group Report';
				break;
			case 'deprecation':
				$hrefs[] = '<a href="https://wicg.github.io/deprecation-reporting/">Deprecation Reporting</a> Draft Community Group Report';
				break;
			case 'intervention':
				$hrefs[] = '<a href="https://wicg.github.io/intervention-reporting/">Intervention Reporting</a> Draft Community Group Report';
				break;
			case 'nel':
				$hrefs[] = '<a href="https://www.w3.org/TR/network-error-logging/">Network Error Logging</a> Working Draft';
				break;
			case 'expect-ct':
				$hrefs[] = '<a href="https://www.rfc-editor.org/rfc/rfc9163">Expect-CT Extension for HTTP</a> (RFC 9163)';
				$hrefs[] = '<a href="https://certificate.transparency.dev/logs/">Known Certificate Transparency logs</a>';
				$hrefs[] = '<a href="https://github.com/GoogleChrome/CertificateTransparency/blob/master/ct_policy.md">Chrome\'s CT policy</a>';
				$hrefs[] = '<a href="https://support.apple.com/en-us/HT205280">Apple\'s CT policy</a>';
				$hrefs[] = '<a href="https://crt.sh/">crt.sh</a> Certificate Search';
				break;
			case 'caa':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc8659">DNS Certification Authority Authorization (CAA) Resource Record</a> (RFC 8659)';
				break;
			case 'dmarc':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc7489">Domain-based Message Authentication, Reporting, and Conformance (DMARC)</a> (RFC 7489)';
				break;
			case 'smtp-tlsrpt':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc8460">SMTP TLS Reporting</a> (RFC 8460)';
				break;
			case 'spf':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc7208">Sender Policy Framework (SPF)</a> (RFC 7208)';
				break;
			case 'dkim':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc6376">DomainKeys Identified Mail (DKIM)</a> (RFC 6376)';
				break;
			case 'mta-sts':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc8461">SMTP MTA Strict Transport Security (MTA-STS)</a> (RFC 8461)';
				break;
			case 'dane':
				$hrefs[] = '<a href="https://tools.ietf.org/html/rfc6698">DNS-Based Authentication of Named Entities (DANE)</a> (RFC 6698)';
				break;
			case 'trusted-types':
				$hrefs[] = '<a href="https://www.michalspacek.com/talks/dom-xss-and-trusted-types-owaspcz">My article about <abbr title="Document Object Model">DOM</abbr> <abbr title="Cross-Site Scripting">XSS</abbr> and Trusted Types</a> (also available <a href="https://www.michalspacek.cz/prednasky/jak-princezna-finalne-zatocila-s-dom-xss-jsdays">in Czech</a>)';
				$hrefs[] = '<a href="https://w3c.github.io/trusted-types/dist/spec/">Trusted Types</a> Editor\'s Draft';
				$hrefs[] = '<a href="https://web.dev/articles/trusted-types">Prevent DOM-based cross-site scripting vulnerabilities with Trusted Types</a> on web.dev';
				$hrefs[] = '<a href="https://developer.mozilla.org/en-US/docs/Web/API/Trusted_Types_API">Trusted Types API</a> on MDN';
				break;
			case 'permissions-policy':
				$hrefs[] = <<< 'EOT'
					<a href="https://www.w3.org/TR/permissions-policy/">Permissions Policy</a> Working Draft
					<ul>
						<li><small><a href="https://w3c.github.io/webappsec-permissions-policy/">Permissions Policy</a> Editor's Draft</small></li>
					</ul>
				EOT;
				$hrefs[] = '<a href="https://github.com/w3c/webappsec-permissions-policy/blob/main/permissions-policy-explainer.md">Permissions Policy explainer</a>';
				break;
		}
	}
	$output = '';
	foreach ($hrefs as $href) {
		$output .= "<li>{$href}</li>";
	}

	return "<h2>Related specs & documents</h2><ul>{$output}</ul>";
}
