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


function pageHead(?string $title = null): string
{
	return '<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>' . ($title ? " {$title} | " : '') . 'Reporting API Demos</title>
		<link rel="icon" type="image/svg+xml" href="' . \htmlspecialchars(baseOrigin()) . '/assets/favicon.svg">
		<link rel="alternate icon" href="' . \htmlspecialchars(baseOrigin()) . '/assets/favicon.png">
		<link rel="stylesheet" href="' . \htmlspecialchars(baseOrigin()) . '/assets/style.css">
		<script src="' . \htmlspecialchars(baseOrigin()) . '/assets/scripts.js"></script>
		<script src="' . \htmlspecialchars(baseOrigin()) . '/assets/highlight.pack.js"></script>
		<script src="' . \htmlspecialchars(baseOrigin()) . '/assets/highlight-init.js"></script>
		</head>';
}


function smallReportUriLogoHtml(): string
{
	return '<a href="https://report-uri.com/" target="_blank" rel="noreferrer noopener"><img src="' . \htmlspecialchars(baseOrigin()) . '/assets/report-uri.svg" alt="report-uri.com logo" width="120" height="21" class="supported-by-inline"></a>';
}


function headerHtml(string $header): string
{
	return '<div id="header"><a href="' . \htmlspecialchars(baseOrigin()) . '/"><strong>' . \htmlspecialchars($header) . '</strong></a> <span><span class="separator">&mdash;</span><span class="separator-break"></span> Supported by ' . smallReportUriLogoHtml() . '</span></div>';
}


function footerHtml(): string
{
	return '<div id="footer">
		By <a href="https://www.michalspacek.com">Michal Špaček</a>, <a href="https://twitter.com/spazef0rze">@spazef0rze</a>,
		supported by ' . smallReportUriLogoHtml() . ' &ndash; real time security monitoring and error tracking
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
	return reportOrigin() . '/report.php';
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
	return \htmlspecialchars(
		\preg_replace(
			'/^(  +?)\\1(?=[^ ])/m',
			'$1',
			\rawurldecode(\json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
		)
	);
}


function reports(\PDOStatement $statement, ?int &$seen): string
{
	$result = [];
	$cookieSeen = $_COOKIE['seen'] ?? 0;
	foreach ($statement as $row) {
		if ($seen === null) {
			$seen = \strtotime($row['received']);
		}
		$counts = \array_count_values(\json_decode($row['types']));
		$types = [];
		foreach ($counts as $type => $count) {
			$types[] = "{$count}× $type";
		}
		$reports = \json_decode($row['report'], true);
		$who = (isset($row['who']) ? \htmlspecialchars($row['who']) : null);
		$result[] = \sprintf('<p>%s%s <small>%s</small> <strong>%s</strong>%s%s</p><pre><code class="json">%s</code></pre>',
			(strtotime($row['received']) > $cookieSeen ? '🆕 ' : ''),
			\htmlspecialchars($row['received']),
			\htmlspecialchars(\date_default_timezone_get()),
			\htmlspecialchars(\implode(' + ', $types)),
			(isset($reports[0]) && \is_array($reports[0]) ? ' via Reporting API' : ''),
			(isset($who) ? ' from <code><a href="' . reportCanHasOrigin($who) . '"><strong>' . $who . '</strong></a></code>' : ''),
			jsonReportHtml($reports)
		);
	}

	if ($statement->rowCount() === 0) {
		return '<p>No reports yet</p>';
	} else {
		return \implode("\n", $result);
	}
}


function maxAge(): int
{
	return 30 * 60;
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


function reportToHeaderHtml(string $header, string $groupDescriptionHtml): string
{
	return '<h2>The <code>Report-To</code> response header:</h2>
		<pre><code class="json">' . \htmlspecialchars($header) . '</code></pre>
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


function willTriggerReportToHtml(string $what = 'violation'): string
{
	return "Will trigger a report that will be sent asynchronously, possibly grouped with other reports ({$what} visible in Developer Tools in the <em>Console</em> tab, you won't see the report in <em>Network</em> tab but you can still"
		. ' <a href="https://www.michalspacek.com/chrome-err_spdy_protocol_error-and-an-invalid-http-header#chrome-71-and-newer">view the reporting requests</a>)';
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
			<pre id="source' . $counter . '" hidden><code class="html">' . \htmlspecialchars($source) . '</code></pre>';
	});
}


function scriptSourceHtmlEnd(): bool
{
	return \ob_end_flush();
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
						<li><small>
							Notable changes in the Editor's Draft are switching to structured headers (<code>Reporting-Endpoints</code> instead of <code>Report-To</code>) and moving out concrete reports into the following separate Draft Community Group Reports:
							<a href="https://wicg.github.io/crash-reporting/">Crash Reporting</a>,
							<a href="https://wicg.github.io/deprecation-reporting/">Deprecation Reporting</a>,
							<a href="https://wicg.github.io/intervention-reporting/">Intervention Reporting</a>
						</small></li>
					</ul>
				EOT;
				break;
			case 'nel':
				$hrefs[] = '<a href="https://www.w3.org/TR/network-error-logging/">Network Error Logging</a> Working Draft';
				break;
			case 'expect-ct':
				$hrefs[] = '<a href="https://tools.ietf.org/html/draft-ietf-httpbis-expect-ct">Expect-CT Extension for HTTP</a> Internet-Draft';
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
		}
	}
	$output = '';
	foreach ($hrefs as $href) {
		$output .= "<li>{$href}</li>";
	}

	return "<h2>Related specs</h2><ul>{$output}</ul>";
}
